<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\Mapel;
use App\Models\Periode;
use App\Models\User;
use App\Models\WaliKelas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSantri = Santri::where('status', 'aktif')->count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $periodeAktif = Periode::where('is_active', true)->first();
        $totalGuru = User::whereIn('role', ['guru', 'wali_kelas'])->count();
        $waliKelasTerisi = $periodeAktif
            ? WaliKelas::where('periode_id', $periodeAktif->id)->distinct('kelas_id')->count('kelas_id')
            : 0;
        $kelasMapelTerisi = $periodeAktif
            ? KelasMapel::where('periode_id', $periodeAktif->id)->distinct('kelas_id')->count('kelas_id')
            : 0;

        $setupChecklist = [
            [
                'label' => 'Periode aktif sudah ditetapkan',
                'description' => 'Dibutuhkan agar input nilai, rekap, dan kenaikan kelas bisa berjalan.',
                'complete' => (bool) $periodeAktif,
                'route' => 'periode.index',
            ],
            [
                'label' => 'Data guru dan wali kelas tersedia',
                'description' => 'Minimal ada akun guru atau wali kelas untuk penugasan mengajar.',
                'complete' => $totalGuru > 0,
                'route' => 'users.index',
            ],
            [
                'label' => 'Data kelas sudah dibuat',
                'description' => 'Kelas menjadi tempat pengelompokan santri dan mapel.',
                'complete' => $totalKelas > 0,
                'route' => 'kelas.index',
            ],
            [
                'label' => 'Mata pelajaran sudah dibuat',
                'description' => 'Mapel dipakai untuk penugasan guru dan perhitungan nilai.',
                'complete' => $totalMapel > 0,
                'route' => 'mapel.index',
            ],
            [
                'label' => 'Data santri aktif sudah tersedia',
                'description' => 'Santri aktif akan muncul pada input nilai dan rekap raport.',
                'complete' => $totalSantri > 0,
                'route' => 'santri.index',
            ],
            [
                'label' => 'Wali kelas sudah ditugaskan',
                'description' => 'Pastikan setiap kelas punya wali kelas pada periode aktif.',
                'complete' => $totalKelas > 0 && $waliKelasTerisi >= $totalKelas,
                'route' => 'kelas.manage_wali',
            ],
            [
                'label' => 'Mapel dan guru pengampu sudah diatur',
                'description' => 'Setiap kelas perlu mapel, guru pengampu, dan KKM sebelum nilai diinput.',
                'complete' => $totalKelas > 0 && $kelasMapelTerisi >= $totalKelas,
                'route' => 'kelas.index',
            ],
        ];

        // Optional: Recent Activity (Mocked for now as we don't have an activity log table)
        // A simple "real" activity could be latest grades input.
        // For now let's just pass the stats.
        
        return view('dashboard', compact('totalSantri', 'totalKelas', 'totalMapel', 'periodeAktif', 'setupChecklist'));
    }
}
