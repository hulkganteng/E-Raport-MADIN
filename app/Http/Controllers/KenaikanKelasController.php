<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Periode;
use App\Models\RiwayatKelas;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KenaikanKelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::withCount(['santri' => function ($query) {
            $query->where('status', 'aktif');
        }])->get();
        
        return view('kenaikan.index', compact('kelasList'));
    }

    public function show(Kelas $kelas)
    {
        $santriList = $kelas->santri()->where('status', 'aktif')->get();
        $targetKelasList = Kelas::where('id', '!=', $kelas->id)->get();
        
        // Determine graduation eligibility
        $canGraduate = false;
        if (($kelas->tingkatan == 'ula' && $kelas->tingkat == 3) || 
            ($kelas->tingkatan == 'wustho' && $kelas->tingkat == 6)) {
            $canGraduate = true;
        }

        return view('kenaikan.show', compact('kelas', 'santriList', 'targetKelasList', 'canGraduate'));
    }

    public function process(Request $request, Kelas $kelas)
    {
        $request->validate([
            'santri_ids' => 'required|array',
            'action' => 'required|in:promote,graduate,retain',
            'target_kelas_id' => 'required_if:action,promote|exists:kelas,id',
        ]);

        $activePeriode = Periode::where('is_active', true)->first();

        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        // Check Semester Logic
        if ($activePeriode->semester == 'ganjil') {
            return back()->with('error', 'Kenaikan kelas hanya dapat dilakukan pada semester Genap.');
        }

        DB::transaction(function () use ($request, $kelas, $activePeriode) {
            $santriIds = $request->santri_ids;
            $action = $request->action;
            
            foreach ($santriIds as $santriId) {
                $santri = Santri::find($santriId);
                if (!$santri) continue;

                // 1. Record history
                RiwayatKelas::create([
                    'santri_id' => $santri->id,
                    'kelas_id' => $kelas->id,
                    'periode_id' => $activePeriode->id,
                    'status' => $action === 'promote' ? 'naik_kelas' : ($action === 'graduate' ? 'lulus' : 'tinggal_kelas'),
                ]);

                // 2. Perform Action
                if ($action === 'promote') {
                    $santri->kelas_id = $request->target_kelas_id;
                    $santri->save();
                } elseif ($action === 'graduate') {
                    $santri->status = 'lulus';
                    // Optional: remove from kelas_id or keep last class? Usually keep last class for record.
                    // But if we want to remove from class list, set null?
                    // User requirement: "Fitur Kelulusan". Let's assume keeping details but status 'lulus' excludes from active queries.
                    $santri->save();
                } 
                // If retain, do nothing to santri table (stays in same class), just recorded in history as 'tinggal_kelas'?
                // Or maybe 'tinggal_kelas' means they repeat? If they repeat, they just stay.
            }
        });

        return redirect()->route('kenaikan.index')->with('success', 'Proses kenaikan/kelulusan berhasil.');
    }
}
