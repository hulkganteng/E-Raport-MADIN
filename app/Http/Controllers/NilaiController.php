<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\NilaiMapel;
use App\Models\Periode;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Logic: Show list of Mapels assigned to this teacher (or all if admin)
        // Grouped by Class
        
        if ($user->role == 'super_admin' || $user->role == 'kepsek') {
            $kelas = Kelas::with(['kelas_mapel.mapel', 'kelas_mapel.guru'])->get();
        } else {
            // For Guru / Wali Kelas, show only assigned mapels or classes they manage
            // Simplification: Show all classes, but filter mapels inside view or query
            // Better: Get KelasMapel where guru_id = user->id
            
            // Fetch assignments where this user is the teacher
            $assignments = KelasMapel::with(['kelas', 'mapel'])
                            ->where('guru_id', $user->id)
                            ->orderBy('kelas_id')
                            ->get()
                            ->groupBy('kelas.nama_kelas');
                            
            return view('nilai.index_guru', compact('assignments'));
        }

        return view('nilai.index_admin', compact('kelas'));
    }

    public function input($kelas_mapel_id)
    {
        $kelasMapel = KelasMapel::with(['kelas', 'mapel'])->findOrFail($kelas_mapel_id);
        
        // Ensure active periode exists
        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
            return back()->with('error', 'Akses ditolak. Tidak ada Periode Aktif.');
        }

        // Get Santri in this class
        $santris = Santri::where('kelas_id', $kelasMapel->kelas_id)
                    ->where('status', 'aktif')
                    ->orderBy('nama_lengkap')
                    ->get();

        // Get existing Grades for this periode
        $existingGrades = NilaiMapel::where('kelas_mapel_id', $kelasMapel->id)
                          ->where('periode_id', $periode->id)
                          ->get()
                          ->keyBy('santri_id');

        return view('nilai.input', compact('kelasMapel', 'santris', 'existingGrades', 'periode'));
    }

    public function store(Request $request, $kelas_mapel_id)
    {
        $kelasMapel = KelasMapel::with('mapel')->findOrFail($kelas_mapel_id);
        
        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
             return back()->with('error', 'Tidak ada periode aktif. Data tidak disimpan.');
        }
        
        $grades = $request->input('nilai', []);
        
        DB::beginTransaction();
        try {
            foreach ($grades as $santri_id => $score) {
                // Calculate Final Score
                $harian = $score['harian'] ?? 0;
                $ujian = $score['ujian'] ?? 0;
                
                $bobotH = $kelasMapel->mapel->bobot_harian / 100;
                $bobotU = $kelasMapel->mapel->bobot_ujian / 100;
                
                $akhir = ($harian * $bobotH) + ($ujian * $bobotU);
                
                // Determine Predikat (Simple Logic)
                $predikat = 'D';
                if ($akhir >= 85) $predikat = 'A';
                elseif ($akhir >= 75) $predikat = 'B';
                elseif ($akhir >= 60) $predikat = 'C';

                NilaiMapel::updateOrCreate(
                    [
                        'kelas_mapel_id' => $kelasMapel->id,
                        'santri_id' => $santri_id,
                        'periode_id' => $periode->id
                    ],
                    [
                        'nilai_harian' => $harian,
                        'nilai_ujian' => $ujian,
                        'nilai_akhir' => $akhir,
                        'predikat' => $predikat
                    ]
                );
            }
            DB::commit();
            return back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }
}
