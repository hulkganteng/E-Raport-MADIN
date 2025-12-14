<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\NilaiMapel;
use App\Models\Periode;
use App\Models\RekapNilai;
use App\Models\Santri;
use Illuminate\Http\Request;

class PublicRapotController extends Controller
{
    public function showForm()
    {
        $periode = Periode::where('is_active', true)->first();
        return view('public.cek_nilai', compact('periode'));
    }

    public function check(Request $request)
    {
        $data = $request->validate([
            'nis' => 'required|string|max:50',
            'nama_lengkap' => 'required|string|max:120',
        ]);

        $periode = Periode::where('is_active', true)->first();
        if (!$periode) {
            return back()->withErrors([
                'nis' => 'Periode aktif belum ditetapkan. Silakan hubungi admin.',
            ])->withInput();
        }

        $santri = Santri::with(['kelas', 'biodata'])
            ->where('nis', $data['nis'])
            ->whereRaw('LOWER(nama_lengkap) = ?', [strtolower($data['nama_lengkap'])])
            ->first();

        if (!$santri) {
            return back()->withErrors([
                'nis' => 'Santri tidak ditemukan. Pastikan NIS dan nama lengkap sesuai.',
            ])->withInput();
        }

        $this->ensureRekapExists($santri, $periode);

        $rekap = RekapNilai::where('santri_id', $santri->id)
            ->where('periode_id', $periode->id)
            ->first();

        $absensi = Absensi::where('santri_id', $santri->id)
            ->where('periode_id', $periode->id)
            ->first();

        $nilaiMapel = NilaiMapel::with(['kelasMapel.mapel'])
            ->where('santri_id', $santri->id)
            ->where('periode_id', $periode->id)
            ->get();

        $totalSantri = RekapNilai::where('periode_id', $periode->id)
            ->whereHas('santri', function ($q) use ($santri) {
                $q->where('kelas_id', $santri->kelas_id);
            })
            ->count();

        return view('public.rapor', compact('santri', 'periode', 'rekap', 'absensi', 'nilaiMapel', 'totalSantri'));
    }

    private function ensureRekapExists(Santri $santri, Periode $periode): void
    {
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
