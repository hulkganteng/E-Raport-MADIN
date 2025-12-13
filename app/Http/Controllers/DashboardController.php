<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Periode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSantri = Santri::where('status', 'aktif')->count();
        $totalKelas = Kelas::count();
        $totalMapel = Mapel::count();
        $periodeAktif = Periode::where('is_active', true)->first();

        // Optional: Recent Activity (Mocked for now as we don't have an activity log table)
        // A simple "real" activity could be latest grades input.
        // For now let's just pass the stats.
        
        return view('dashboard', compact('totalSantri', 'totalKelas', 'totalMapel', 'periodeAktif'));
    }
}
