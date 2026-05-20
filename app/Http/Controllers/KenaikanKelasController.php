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
        $isUlaFinal = $kelas->tingkatan === 'ula' && (int) $kelas->tingkat === 3;
        $isWusthoFinal = $kelas->tingkatan === 'wustho' && (int) $kelas->tingkat === 6;
        $canGraduate = $isUlaFinal || $isWusthoFinal;
        $canContinueToWustho = $isUlaFinal;

        $targetKelasList = $canContinueToWustho
            ? Kelas::where('tingkatan', 'wustho')->get()
            : Kelas::where('id', '!=', $kelas->id)->get();

        return view('kenaikan.show', compact(
            'kelas',
            'santriList',
            'targetKelasList',
            'canGraduate',
            'canContinueToWustho',
            'isUlaFinal',
            'isWusthoFinal'
        ));
    }

    public function process(Request $request, Kelas $kelas)
    {
        $request->validate([
            'santri_ids' => 'required|array|min:1',
            'santri_ids.*' => 'integer|distinct|exists:santri,id',
            'action' => 'required|in:promote,graduate,graduate_continue,retain',
            'target_kelas_id' => 'nullable|required_if:action,promote,graduate_continue|exists:kelas,id',
        ]);

        $activePeriode = Periode::where('is_active', true)->first();

        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        // Check Semester Logic
        if ($activePeriode->semester == 'ganjil') {
            return back()->with('error', 'Kenaikan kelas hanya dapat dilakukan pada semester Genap.');
        }

        $isUlaFinal = $kelas->tingkatan === 'ula' && (int) $kelas->tingkat === 3;
        $isWusthoFinal = $kelas->tingkatan === 'wustho' && (int) $kelas->tingkat === 6;
        $selectedSantriIds = collect($request->input('santri_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
        $validSantriCount = Santri::where('kelas_id', $kelas->id)
            ->where('status', 'aktif')
            ->whereIn('id', $selectedSantriIds)
            ->count();

        if ($validSantriCount !== $selectedSantriIds->count()) {
            return back()->with('error', 'Santri yang dipilih harus santri aktif pada kelas ini.')->withInput();
        }

        if (in_array($request->action, ['promote', 'graduate_continue'], true)
            && (int) $request->target_kelas_id === (int) $kelas->id) {
            return back()->with('error', 'Target kelas baru tidak boleh sama dengan kelas asal.')->withInput();
        }

        if ($request->action === 'graduate_continue' && !$isUlaFinal) {
            return back()->with('error', 'Lulus dan lanjut ke Wustho hanya berlaku untuk kelas akhir Ula.');
        }

        if ($request->action === 'graduate' && !$isUlaFinal && !$isWusthoFinal) {
            return back()->with('error', 'Kelulusan hanya berlaku untuk kelas 3 Ula atau 6 Wustho.');
        }

        if ($request->action === 'promote' && $isUlaFinal) {
            return back()->with('error', 'Untuk kelas 3 Ula, gunakan aksi Lulus Ula & Lanjut Wustho.');
        }

        if ($request->action === 'graduate_continue') {
            $targetKelas = Kelas::find($request->target_kelas_id);

            if (!$targetKelas || $targetKelas->tingkatan !== 'wustho') {
                return back()->with('error', 'Target lanjutan kelas 3 Ula harus kelas Wustho.');
            }
        }

        DB::transaction(function () use ($request, $kelas, $activePeriode, $isUlaFinal) {
            $santriIds = $request->santri_ids;
            $action = $request->action;
            
            foreach ($santriIds as $santriId) {
                $santri = Santri::where('kelas_id', $kelas->id)->find($santriId);
                if (!$santri) continue;

                $historyStatus = match ($action) {
                    'promote' => 'naik_kelas',
                    'graduate_continue' => 'lulus_ula_lanjut_wustho',
                    'graduate' => $isUlaFinal ? 'lulus_ula' : 'lulus',
                    default => 'tinggal_kelas',
                };

                RiwayatKelas::create([
                    'santri_id' => $santri->id,
                    'kelas_id' => $kelas->id,
                    'periode_id' => $activePeriode->id,
                    'status' => $historyStatus,
                ]);

                if ($action === 'promote' || $action === 'graduate_continue') {
                    $santri->kelas_id = $request->target_kelas_id;
                    $santri->status = 'aktif';
                    $santri->save();
                } elseif ($action === 'graduate') {
                    $santri->status = 'lulus';
                    $santri->save();
                }
            }
        });

        return redirect()->route('kenaikan.index')->with('success', 'Proses kenaikan/kelulusan berhasil.');
    }
}
