<?php

namespace App\Http\Controllers;

use App\Support\ChromePdfRenderer;
use App\Models\Kelas;
use App\Models\Periode;
use App\Models\RekapNilai;
use App\Models\Santri;
use App\Models\NilaiMapel;
use App\Models\Absensi;
use App\Models\KelasMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if (!$isWaliKelas && auth()->user()->role !== 'super_admin') {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Anda bukan Wali Kelas untuk kelas ini pada periode aktif.');
        }

        $this->recalculateClassRekap($kelas, $periode);

        $rekaps = RekapNilai::with(['santri'])
                    ->whereHas('santri', function($q) use ($kelas) {
                        $q->where('kelas_id', $kelas->id);
                    })
                    ->where('periode_id', $periode->id)
                    ->get()
                    ->sortByDesc('total_nilai'); // Sort by total score for display

        $kelasMapels = KelasMapel::with('mapel')
            ->where('kelas_id', $kelas->id)
            ->where('periode_id', $periode->id)
            ->get()
            ->sortBy(fn ($kelasMapel) => ($kelasMapel->mapel->kategori ?? '') . '-' . ($kelasMapel->mapel->nama_mapel ?? ''))
            ->values();

        $nilaiBySantri = NilaiMapel::whereIn('santri_id', $rekaps->pluck('santri_id'))
            ->where('periode_id', $periode->id)
            ->whereHas('kelasMapel', function ($query) use ($kelas, $periode) {
                $query->where('kelas_id', $kelas->id)
                    ->where('periode_id', $periode->id);
            })
            ->get()
            ->groupBy('santri_id')
            ->map(fn ($items) => $items->keyBy('kelas_mapel_id'));

        $absensiBySantri = Absensi::whereIn('santri_id', $rekaps->pluck('santri_id'))
            ->where('periode_id', $periode->id)
            ->get()
            ->keyBy('santri_id');

        $canEditAllNilai = auth()->user()->role === 'super_admin';
        $editableKelasMapelIds = $canEditAllNilai
            ? $kelasMapels->pluck('id')
            : $kelasMapels->where('guru_id', auth()->id())->pluck('id');

        return view('rekap.index', compact('kelas', 'periode', 'rekaps', 'periodes', 'kelasMapels', 'nilaiBySantri', 'absensiBySantri', 'editableKelasMapelIds'));
    }

    public function generateRanking(Kelas $kelas)
    {
        $periode = Periode::where('is_active', true)->firstOrFail();
        if (!$this->canAccessKelasRapot($kelas, $periode)) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Anda bukan Wali Kelas untuk kelas ini pada periode aktif.');
        }
        
        // 1. Calculate Total Score for each Santri
        $santris = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->get();
        
        DB::beginTransaction();
        try {
            $this->recalculateClassRekap($kelas, $periode);
            
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

        if (!$isWaliKelas && $user->role !== 'super_admin') {
            return back()->with('error', 'Akses Ditolak. Hanya Wali Kelas yang dapat mengisi Sikap dan Absensi.');
        }

        $request->validate([
            'sakit' => 'nullable|integer|min:0',
            'izin' => 'nullable|integer|min:0',
            'alpha' => 'nullable|integer|min:0',
            'akhlaq' => 'nullable|in:A,B,C,D',
            'kerajinan' => 'nullable|in:A,B,C,D',
            'kedisiplinan' => 'nullable|in:A,B,C,D',
            'kerapihan' => 'nullable|in:A,B,C,D',
            'catatan_wali' => 'nullable|string|max:1000',
            'nilai' => 'nullable|array',
        ]);

        $kelas = $rekap->santri->kelas;
        $periode = Periode::findOrFail($rekap->periode_id);
        $kelasMapels = KelasMapel::with('mapel')
            ->where('kelas_id', $kelas->id)
            ->where('periode_id', $periode->id)
            ->get()
            ->keyBy('id');
        $canEditAllNilai = $user->role === 'super_admin';
        $editableKelasMapelIds = $canEditAllNilai
            ? $kelasMapels->keys()->map(fn ($id) => (int) $id)->all()
            : $kelasMapels->where('guru_id', $user->id)->keys()->map(fn ($id) => (int) $id)->all();

        $errors = [];
        $parsedGrades = [];

        foreach ($request->input('nilai', []) as $kelasMapelId => $score) {
            if (!$kelasMapels->has((int) $kelasMapelId)) {
                continue;
            }
            if (!in_array((int) $kelasMapelId, $editableKelasMapelIds, true)) {
                continue;
            }

            $existing = NilaiMapel::where('santri_id', $rekap->santri_id)
                ->where('periode_id', $rekap->periode_id)
                ->where('kelas_mapel_id', $kelasMapelId)
                ->exists();

            $harian = $this->parseNumber($score['harian'] ?? null);
            $ujian = $this->parseNumber($score['ujian'] ?? null);

            if ($harian === null && $ujian === null && !$existing) {
                continue;
            }

            $harian = $harian ?? 0;
            if ($ujian === null) {
                $errors["nilai.$kelasMapelId.ujian"] = 'Nilai ujian wajib diisi jika nilai mapel diubah.';
                continue;
            }

            if ($harian < 0 || $harian > 100) {
                $errors["nilai.$kelasMapelId.harian"] = 'Nilai harian harus 0-100.';
            }
            if ($ujian < 0 || $ujian > 100) {
                $errors["nilai.$kelasMapelId.ujian"] = 'Nilai ujian harus 0-100.';
            }

            $parsedGrades[$kelasMapelId] = ['harian' => $harian, 'ujian' => $ujian];
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }

        DB::transaction(function () use ($request, $rekap, $kelas, $periode, $kelasMapels, $parsedGrades) {
            $rekap->update($request->only([
                'akhlaq', 'kerajinan', 'kedisiplinan', 'kerapihan',
                'catatan_wali', 'keputusan'
            ]));

            Absensi::updateOrCreate(
                ['santri_id' => $rekap->santri_id, 'periode_id' => $rekap->periode_id],
                [
                    'sakit' => $request->input('sakit', 0),
                    'izin' => $request->input('izin', 0),
                    'alpha' => $request->input('alpha', 0),
                ]
            );

            foreach ($parsedGrades as $kelasMapelId => $score) {
                $kelasMapel = $kelasMapels->get((int) $kelasMapelId);
                $akhir = $this->calculateFinalScore($score['harian'], $score['ujian'], $kelasMapel);

                NilaiMapel::updateOrCreate(
                    [
                        'kelas_mapel_id' => $kelasMapel->id,
                        'santri_id' => $rekap->santri_id,
                        'periode_id' => $rekap->periode_id,
                    ],
                    [
                        'nilai_harian' => $score['harian'],
                        'nilai_ujian' => $score['ujian'],
                        'nilai_akhir' => $akhir,
                        'predikat' => $this->resolvePredikat($akhir),
                    ]
                );
            }

            $this->recalculateClassRekap($kelas, $periode);
        });

        return back()->with('success', 'Data nilai, absensi, dan catatan berhasil disimpan.');
    }

    public function printRapot(Request $request, Santri $santri, ChromePdfRenderer $pdfRenderer)
    {
        $periode = $this->resolvePeriode($request->input('periode_id'));
        $copies = $this->normalizeCopies($request->input('copies', 1));
        $santri->loadMissing('kelas');
        if (!$this->canAccessKelasRapot($santri->kelas, $periode)) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Anda tidak dapat mencetak rapot kelas ini.');
        }
        
        $this->ensureRekapExists($santri->kelas, $periode);

        $missingGrades = $this->missingRapotGrades($santri, $santri->kelas, $periode);
        if (!empty($missingGrades)) {
            return back()->with(
                'error',
                'Rapot ' . $santri->nama_lengkap . ' belum bisa dicetak karena nilai belum lengkap: ' . implode(', ', $missingGrades) . '.'
            );
        }

        $rekap = RekapNilai::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
        $absensi = Absensi::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();

        // Get Grades Grouped by Category
        $nilaiMapel = NilaiMapel::with(['kelasMapel.mapel'])
                    ->where('santri_id', $santri->id)
                    ->where('periode_id', $periode->id)
                    ->whereHas('kelasMapel', function ($query) use ($santri, $periode) {
                        $query->where('kelas_id', $santri->kelas_id)
                            ->where('periode_id', $periode->id);
                    })
                    ->get();
        
        // Calculate Rankings context
        $totalSantri = RekapNilai::where('periode_id', $periode->id)
                        ->whereHas('santri', function($q) use ($santri) {
                            $q->where('kelas_id', $santri->kelas_id);
                        })->count();

        $logoSrc = $pdfRenderer->toFileUrl(public_path('logo.jpg'));

        return $pdfRenderer->streamView('rekap.print', compact(
            'santri',
            'periode',
            'rekap',
            'absensi',
            'nilaiMapel',
            'totalSantri',
            'copies',
            'logoSrc'
        ), 'RAPOT_' . $santri->nama_lengkap . '.pdf');
    }

    public function printAllRapot(Request $request, Kelas $kelas, ChromePdfRenderer $pdfRenderer)
    {
        $periode = $this->resolvePeriode($request->input('periode_id'));
        $copies = $this->normalizeCopies($request->input('copies', 1));
        if (!$this->canAccessKelasRapot($kelas, $periode)) {
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak. Anda tidak dapat mencetak rapot kelas ini.');
        }
        
        // Ensure rekap exists first
        $this->ensureRekapExists($kelas, $periode);
        
        $santris = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $incompleteSantris = $santris
            ->filter(fn ($santri) => !empty($this->missingRapotGrades($santri, $kelas, $periode)))
            ->pluck('nama_lengkap')
            ->values();

        if ($incompleteSantris->isNotEmpty()) {
            $names = $incompleteSantris->take(5)->implode(', ');
            $remaining = $incompleteSantris->count() - 5;
            $suffix = $remaining > 0 ? ' dan ' . $remaining . ' santri lainnya' : '';

            return back()->with(
                'error',
                'Cetak semua rapot ditolak karena masih ada nilai yang belum lengkap untuk: ' . $names . $suffix . '.'
            );
        }
        
        // We will generate one PDF with page breaks
        $data = [];
        foreach($santris as $santri) {
            $rekap = RekapNilai::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
            $absensi = Absensi::where('santri_id', $santri->id)->where('periode_id', $periode->id)->first();
            $nilaiMapel = NilaiMapel::with(['kelasMapel.mapel'])
                        ->where('santri_id', $santri->id)
                        ->where('periode_id', $periode->id)
                        ->whereHas('kelasMapel', function ($query) use ($kelas, $periode) {
                            $query->where('kelas_id', $kelas->id)
                                ->where('periode_id', $periode->id);
                        })
                        ->get();
            
             $totalSantri = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->count();

            $data[] = compact('santri', 'periode', 'rekap', 'absensi', 'nilaiMapel', 'totalSantri');
        }

        $logoSrc = $pdfRenderer->toFileUrl(public_path('logo.jpg'));

        return $pdfRenderer->streamView('rekap.print_all', compact(
            'data',
            'kelas',
            'periode',
            'copies',
            'logoSrc'
        ), 'RAPOT_KELAS_' . $kelas->nama_kelas . '.pdf');
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

    private function missingRapotGrades(Santri $santri, Kelas $kelas, Periode $periode): array
    {
        $kelasMapels = KelasMapel::with('mapel')
            ->where('kelas_id', $kelas->id)
            ->where('periode_id', $periode->id)
            ->get();

        if ($kelasMapels->isEmpty()) {
            return ['mata pelajaran belum diatur'];
        }

        $completeKelasMapelIds = NilaiMapel::where('santri_id', $santri->id)
            ->where('periode_id', $periode->id)
            ->whereNotNull('nilai_ujian')
            ->whereNotNull('nilai_akhir')
            ->pluck('kelas_mapel_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return $kelasMapels
            ->reject(fn ($kelasMapel) => in_array((int) $kelasMapel->id, $completeKelasMapelIds, true))
            ->map(fn ($kelasMapel) => $kelasMapel->mapel->nama_mapel ?? 'Mapel #' . $kelasMapel->id)
            ->values()
            ->all();
    }

    private function recalculateClassRekap(Kelas $kelas, Periode $periode): void
    {
        $santris = Santri::where('kelas_id', $kelas->id)->where('status', 'aktif')->get();

        foreach ($santris as $santri) {
            $nilai = NilaiMapel::where('santri_id', $santri->id)
                ->where('periode_id', $periode->id)
                ->whereHas('kelasMapel', function ($query) use ($kelas, $periode) {
                    $query->where('kelas_id', $kelas->id)
                        ->where('periode_id', $periode->id);
                });

            $totalNilai = (float) $nilai->sum('nilai_akhir');
            $countMapel = (clone $nilai)->count();
            $rataRata = $countMapel > 0 ? $totalNilai / $countMapel : 0;

            RekapNilai::updateOrCreate(
                ['santri_id' => $santri->id, 'periode_id' => $periode->id],
                ['total_nilai' => $totalNilai, 'rata_rata' => $rataRata]
            );
        }

        $rank = 1;
        RekapNilai::whereIn('santri_id', $santris->pluck('id'))
            ->where('periode_id', $periode->id)
            ->orderByDesc('total_nilai')
            ->get()
            ->each(function (RekapNilai $rekap) use (&$rank) {
                $rekap->update(['ranking' => $rank++]);
            });
    }

    private function parseNumber($value): ?float
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (str_contains($value, ',')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? (float) $value : null;
    }

    private function calculateFinalScore(float $harian, float $ujian, KelasMapel $kelasMapel): float
    {
        $bobotHarian = (float) ($kelasMapel->mapel->bobot_harian ?? 40);
        $bobotUjian = (float) ($kelasMapel->mapel->bobot_ujian ?? 60);
        $totalBobot = $bobotHarian + $bobotUjian;

        if ($totalBobot <= 0) {
            $bobotHarian = 40;
            $bobotUjian = 60;
            $totalBobot = 100;
        }

        return round((($harian * $bobotHarian) + ($ujian * $bobotUjian)) / $totalBobot, 2);
    }

    private function resolvePredikat(float $akhir): string
    {
        if ($akhir >= 85) {
            return 'A';
        }
        if ($akhir >= 75) {
            return 'B';
        }
        if ($akhir >= 60) {
            return 'C';
        }

        return 'D';
    }

    private function canAccessKelasRapot(Kelas $kelas, Periode $periode): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        return \App\Models\WaliKelas::where('kelas_id', $kelas->id)
            ->where('periode_id', $periode->id)
            ->where('user_id', $user->id)
            ->exists();
    }
}
