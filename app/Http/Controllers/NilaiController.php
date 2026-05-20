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
        $periode = Periode::where('is_active', true)->firstOrFail();
        
        // Logic: Show list of Mapels assigned to this teacher (or all if admin)
        // Grouped by Class
        
        if ($user->role == 'super_admin' || $user->role == 'kepsek') {
            $kelas = Kelas::with([
                'kelas_mapel' => function ($query) use ($periode) {
                    $query->where('periode_id', $periode->id)->with(['mapel', 'guru']);
                }
            ])->get();
        } else {
            // For Guru / Wali Kelas, show only assigned mapels or classes they manage
            // Simplification: Show all classes, but filter mapels inside view or query
            // Better: Get KelasMapel where guru_id = user->id
            
            // Fetch assignments where this user is the teacher
            $assignments = KelasMapel::with(['kelas', 'mapel'])
                            ->where('guru_id', $user->id)
                            ->where('periode_id', $periode->id)
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
        if ((int) $kelasMapel->periode_id !== (int) $periode->id) {
            return redirect()->route('nilai.index')->with('error', 'Mapel ini bukan bagian dari periode aktif.');
        }
        if (!$this->canAccessKelasMapel($kelasMapel)) {
            return redirect()->route('nilai.index')->with('error', 'Akses ditolak. Anda bukan pengajar mapel ini.');
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

        // Paksa halaman tidak di-cache agar nilai terbaru selalu tampil setelah submit
        return response()
            ->view('nilai.input', compact('kelasMapel', 'santris', 'existingGrades', 'periode'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function store(Request $request, $kelas_mapel_id)
    {
        $kelasMapel = KelasMapel::with('mapel')->findOrFail($kelas_mapel_id);
        
        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
             return back()->with('error', 'Tidak ada periode aktif. Data tidak disimpan.');
        }
        if ((int) $kelasMapel->periode_id !== (int) $periode->id) {
            return redirect()->route('nilai.index')->with('error', 'Mapel ini bukan bagian dari periode aktif.');
        }
        if (!$this->canAccessKelasMapel($kelasMapel)) {
            return redirect()->route('nilai.index')->with('error', 'Akses ditolak. Anda bukan pengajar mapel ini.');
        }

        $rawGrades = $request->input('nilai', []);
        $validSantriIds = Santri::where('kelas_id', $kelasMapel->kelas_id)
            ->where('status', 'aktif')
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        // Parser angka yang menerima format Indonesia (1.234,56) atau titik desimal
        $parseNumber = function ($value) {
            if ($value === null) return null;
            $value = trim((string) $value);
            if ($value === '') return null;
            // Jika ada koma, anggap sebagai desimal Indonesia: buang titik ribuan lalu ganti koma jadi titik
            if (str_contains($value, ',')) {
                $value = str_replace('.', '', $value);
                $value = str_replace(',', '.', $value);
            } else {
                // Tidak ada koma: biarkan titik sebagai desimal, hilangkan koma jika terselip
                $value = str_replace(',', '', $value);
            }
            return is_numeric($value) ? (float) $value : null;
        };

        $errors = [];
        $parsedGrades = [];

        foreach ($rawGrades as $santriId => $score) {
            if (!in_array((string) $santriId, $validSantriIds, true)) {
                continue;
            }

            $harian = $parseNumber($score['harian'] ?? null) ?? 0;
            $ujian = $parseNumber($score['ujian'] ?? null);

            if ($ujian === null) {
                $errors["nilai.$santriId.ujian"] = "Nilai ujian wajib diisi (0-100).";
                continue;
            }

            if ($harian < 0 || $harian > 100) {
                $errors["nilai.$santriId.harian"] = "Nilai harian harus 0-100.";
            }
            if ($ujian < 0 || $ujian > 100) {
                $errors["nilai.$santriId.ujian"] = "Nilai ujian harus 0-100.";
            }

            $parsedGrades[$santriId] = ['harian' => $harian, 'ujian' => $ujian];
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        DB::beginTransaction();
        try {
            $saved = 0;
            $bobotHarian = (float) ($kelasMapel->mapel->bobot_harian ?? 40);
            $bobotUjian = (float) ($kelasMapel->mapel->bobot_ujian ?? 60);
            $totalBobot = $bobotHarian + $bobotUjian;
            if ($totalBobot <= 0) {
                DB::rollBack();
                return back()->with('error', 'Bobot nilai mapel tidak valid. Atur bobot harian dan ujian terlebih dahulu.');
            }

            foreach ($parsedGrades as $santri_id => $score) {
                $harian = $score['harian'];
                $ujian = $score['ujian'];
                
                $akhir = round((($harian * $bobotHarian) + ($ujian * $bobotUjian)) / $totalBobot, 2);
                
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
                $saved++;
            }

            if ($saved === 0) {
                DB::rollBack();
                return back()->with('error', 'Tidak ada nilai yang diproses. Pastikan Nilai Ujian diisi angka 0-100.');
            }
            DB::commit();
            return back()->with('success', 'Nilai berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage());
        }
    }

    private function canAccessKelasMapel(KelasMapel $kelasMapel): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        if (in_array($user->role, ['super_admin', 'kepsek'], true)) {
            return true;
        }

        return (int) $kelasMapel->guru_id === (int) $user->id;
    }
}
