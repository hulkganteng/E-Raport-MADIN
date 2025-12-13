<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Periode;
use App\Models\RekapNilai;
use App\Models\Santri;
use App\Models\NilaiMapel;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RekapController extends Controller
{
    public function indexByKelas(Kelas $kelas)
    {
        $periodes = Periode::orderByDesc('is_active')->orderByDesc('start_date')->get();
        $periode = $periodes->firstWhere('is_active', true) ?? $periodes->first();
        if (!$periode) {
            return redirect()->route('dashboard')->with('error', 'Tidak ada periode aktif atau tersimpan.');
        }
        
        // Ensure Rekap exists for all students
        $this->ensureRekapExists($kelas, $periode);
        
        // Enforce Permission: Only assigned Wali Kelas (for this specific class & period) or Admin can view
        $isWaliKelas = \App\Models\WaliKelas::where('kelas_id', $kelas->id)
                        ->where('periode_id', $periode->id)
                        ->where('user_id', auth()->id())
                        ->exists();

        if (!$isWaliKelas && auth()->user()->role !== 'super_admin' && auth()->user()->role !== 'kepsek') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Anda bukan Wali Kelas untuk kelas ini pada periode aktif.');
        }

        $rekaps = RekapNilai::with(['santri'])
                    ->whereHas('santri', function($q) use ($kelas) {
                        $q->where('kelas_id', $kelas->id);
                    })
                    ->where('periode_id', $periode->id)
                    ->get()
                    ->sortByDesc('total_nilai'); // Sort by total score for display

        return view('rekap.index', compact('kelas', 'periode', 'rekaps', 'periodes'));
    }

    public function generateRanking(Kelas $kelas)
    {
        $periode = Periode::where('is_active', true)->firstOrFail();
        
        // 1. Calculate Total Score for each Santri
        $santris = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->get();
        
        DB::beginTransaction();
        try {
            foreach($santris as $santri) {
                $totalNilai = NilaiMapel::where('santri_id', $santri->id)
                                ->where('periode_id', $periode->id)
                                ->sum('nilai_akhir');
                
                $countMapel = NilaiMapel::where('santri_id', $santri->id)
                                ->where('periode_id', $periode->id)
                                ->count();
                                
                $rataRata = $countMapel > 0 ? $totalNilai / $countMapel : 0;
                
                RekapNilai::updateOrCreate(
                    ['santri_id' => $santri->id, 'periode_id' => $periode->id],
                    ['total_nilai' => $totalNilai, 'rata_rata' => $rataRata]
                );
            }
            
            // 2. Assign Ranking based on Total Score
            $rekaps = RekapNilai::whereIn('santri_id', $santris->pluck('id'))
                        ->where('periode_id', $periode->id)
                        ->orderByDesc('total_nilai')
                        ->get();
            
            $rank = 1;
            foreach($rekaps as $rekap) {
                $rekap->update(['ranking' => $rank++]);
            }
            
            DB::commit();
            return back()->with('success', 'Ranking berhasil dihitung ulang.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hitung ranking: ' . $e->getMessage());
        }
    }

    public function update(Request $request, RekapNilai $rekap)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Enforce Permission: Only assigned Wali Kelas (for this specific class & period) or Admin can edit
        $isWaliKelas = \App\Models\WaliKelas::where('kelas_id', $rekap->santri->kelas_id)
                        ->where('periode_id', $rekap->periode_id)
                        ->where('user_id', $user->id)
                        ->exists();

        if (!$isWaliKelas && $user->role !== 'super_admin' && $user->role !== 'kepsek') {
            return back()->with('error', 'Akses Ditolak. Hanya Wali Kelas yang dapat mengisi Sikap dan Absensi.');
        }

        // Update Attendance & Personality
        $rekap->update($request->only([
            'akhlaq', 'kerajinan', 'kedisiplinan', 'kerapihan', 
            'catatan_wali', 'keputusan'
        ]));
        
        // Update Absensi separately if using separate table
        Absensi::updateOrCreate(
            ['santri_id' => $rekap->santri_id, 'periode_id' => $rekap->periode_id],
            [
                'sakit' => $request->input('sakit', 0),
                'izin' => $request->input('izin', 0),
                'alpha' => $request->input('alpha', 0),
            ]
        );
        
        // Trigger auto-calculation of ranking? Optional.
        // For now just return
        return back()->with('success', 'Data rekap berhasil disimpan');
    }

    public function printRapot(Request $request, Santri $santri)
    {
        $periode = $this->resolvePeriode($request->input('periode_id'));
        $copies = $this->normalizeCopies($request->input('copies', 1));
        $santri->loadMissing('kelas');
        
        $this->ensureRekapExists($santri->kelas, $periode);

        $rekap = RekapNilai::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
        $absensi = Absensi::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();

        // Get Grades Grouped by Category
        $nilaiMapel = NilaiMapel::with(['kelasMapel.mapel'])
                    ->where('santri_id', $santri->id)
                    ->where('periode_id', $periode->id)
                    ->get();
        
        // Calculate Rankings context
        $totalSantri = RekapNilai::where('periode_id', $periode->id)
                        ->whereHas('santri', function($q) use ($santri) {
                            $q->where('kelas_id', $santri->kelas_id);
                        })->count();

        $pdf = Pdf::loadView('rekap.print', compact('santri', 'periode', 'rekap', 'absensi', 'nilaiMapel', 'totalSantri', 'copies'));
        return $pdf->stream('RAPOT_' . $santri->nama_lengkap . '.pdf');
    }

    public function printAllRapot(Request $request, Kelas $kelas)
    {
        $periode = $this->resolvePeriode($request->input('periode_id'));
        $copies = $this->normalizeCopies($request->input('copies', 1));
        
        // Ensure rekap exists first
        $this->ensureRekapExists($kelas, $periode);
        
        $santris = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->orderBy('nama_lengkap')->get();
        
        // We will generate one PDF with page breaks
        $data = [];
        foreach($santris as $santri) {
            $rekap = RekapNilai::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
            $absensi = Absensi::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
            $nilaiMapel = NilaiMapel::with(['kelasMapel.mapel'])
                        ->where('santri_id', $santri->id)
                        ->where('periode_id', $periode->id)
                        ->get();
            
             $totalSantri = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->count();

            $data[] = compact('santri', 'periode', 'rekap', 'absensi', 'nilaiMapel', 'totalSantri');
        }

        $pdf = Pdf::loadView('rekap.print_all', compact('data', 'kelas', 'periode', 'copies'));
        return $pdf->stream('RAPOT_KELAS_' . $kelas->nama_kelas . '.pdf');
    }

    private function ensureRekapExists($kelas, $periode)
    {
        $santris = Santri::where('kelas_id', $kelas->id)->get();
        foreach($santris as $santri) {
            RekapNilai::firstOrCreate(
                ['santri_id' => $santri->id, 'periode_id' => $periode->id],
                ['total_nilai' => 0, 'rata_rata' => 0]
            );
             Absensi::firstOrCreate(
                ['santri_id' => $santri->id, 'periode_id' => $periode->id],
                ['sakit' => 0, 'izin' => 0, 'alpha' => 0]
            );
        }
    }

    private function resolvePeriode(?int $periodeId): Periode
    {
        if ($periodeId) {
            $periode = Periode::find($periodeId);
            if ($periode) {
                return $periode;
            }
        }

        return Periode::where('is_active', true)->firstOrFail();
    }

    private function normalizeCopies($copies): int
    {
        $copies = (int) $copies;
        return max(1, min($copies, 10));
    }
}
