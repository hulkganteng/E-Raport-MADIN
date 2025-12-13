<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Mapel;
use App\Models\KelasMapel;
use Illuminate\Http\Request;

use App\Models\Periode;
use App\Models\WaliKelas;

class KelasController extends Controller
{
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $activePeriode = Periode::where('is_active', true)->first();
        
        // Check if user is assigned as Wali Kelas in the active period
        $assignedKelasIds = collect();
        if ($activePeriode) {
            $assignedKelasIds = WaliKelas::where('user_id', $user->id)
                                ->where('periode_id', $activePeriode->id)
                                ->pluck('kelas_id');
        }

        // Logic display:
        // 1. Super Admin / Admin / Kepsek/Staff -> Lihat Semua
        // 2. Wali Kelas (Role explicit OR Assigned dynamic) -> Lihat Assigned Only
        
        $isAdmin = in_array($user->role, ['super_admin', 'admin', 'kepsek', 'staff']); // Adjust roles as needed

        if (!$isAdmin && ($user->role == 'wali_kelas' || $assignedKelasIds->isNotEmpty())) {
            if ($activePeriode) {
                $kelas = Kelas::whereIn('id', $assignedKelasIds)
                            ->with(['current_wali_kelas.user', 'kelas_mapel'])
                            ->get();
            } else {
                $kelas = collect(); 
            }
        } else {
            // Admin sees all
            $kelas = Kelas::with(['current_wali_kelas.user', 'kelas_mapel'])->get();
        }

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $wali_kelas = User::where('role', 'wali_kelas')->get();
        return view('kelas.create', compact('wali_kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'tingkatan' => 'required|in:ula,wustho',
            'tahun_ajar' => 'required',
        ]);

        Kelas::create($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required',
            'tingkatan' => 'required|in:ula,wustho',
            'tahun_ajar' => 'required',
        ]);

        $kelas->update($request->all());

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    public function manageWali()
    {
        $activePeriode = Periode::where('is_active', true)->first();
        $kelas = Kelas::all();
        
        // Overview assignments
        $assignments = collect();
        if ($activePeriode) {
            $assignments = WaliKelas::where('periode_id', $activePeriode->id)
                            ->with('user')
                            ->get()
                            ->keyBy('kelas_id');
        }

        return view('kelas.manage_wali', compact('kelas', 'activePeriode', 'assignments'));
    }

    public function editWali(Kelas $kelas)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        // Get currently assigned wali for this class
        $currentAssignment = WaliKelas::where('kelas_id', $kelas->id)
                            ->where('periode_id', $activePeriode->id)
                            ->first();

        // Get all potential gurus
        // Filter: Must be guru/wali_kelas AND (Not assigned to any other class OR assigned to THIS class)
        $assignedUserIdsInOtherClasses = WaliKelas::where('periode_id', $activePeriode->id)
                                            ->where('kelas_id', '!=', $kelas->id)
                                            ->pluck('user_id')
                                            ->toArray();

        $gurus = User::whereIn('role', ['guru', 'wali_kelas'])
                    ->whereNotIn('id', $assignedUserIdsInOtherClasses)
                    ->get();

        return view('kelas.assign_wali', compact('kelas', 'gurus', 'activePeriode', 'currentAssignment'));
    }

    public function updateWaliSingle(Request $request, Kelas $kelas)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }

        $request->validate([
            'user_id' => 'nullable|exists:users,id'
        ]);

        $userId = $request->input('user_id');

        if ($userId) {
            // Validate availability again
            $exists = WaliKelas::where('periode_id', $activePeriode->id)
                        ->where('user_id', $userId)
                        ->where('kelas_id', '!=', $kelas->id)
                        ->exists();
            
            if ($exists) {
                return back()->with('error', 'Guru tersebut sudah menjadi Wali Kelas di kelas lain pada periode ini.');
            }

            WaliKelas::updateOrCreate(
                ['kelas_id' => $kelas->id, 'periode_id' => $activePeriode->id],
                ['user_id' => $userId]
            );
        } else {
            // Remove assignment
            WaliKelas::where('kelas_id', $kelas->id)
                ->where('periode_id', $activePeriode->id)
                ->delete();
        }

        return redirect()->route('kelas.manage_wali')->with('success', 'Wali Kelas berhasil diperbarui untuk ' . $kelas->nama_kelas);
    }

    // Deprecated bulk update kept for compatibility if needed, but UI will change
    public function updateWali(Request $request)
    {
        return $this->updateWaliSingle($request, Kelas::first()); // Placeholder, logic moved to single
    }
    
    public function manageMapel(Kelas $kelas)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        
        // Get all mapels available for this tingkatan or 'all'
        $available_mapels = Mapel::whereIn('tingkatan', [$kelas->tingkatan, 'all'])->get();
        
        // Get currently assigned mapels for ACTIVE PERIOD
        $assignments = collect();
        if ($activePeriode) {
            $assignments = KelasMapel::where('kelas_id', $kelas->id)
                            ->where('periode_id', $activePeriode->id)
                            ->get()
                            ->keyBy('mapel_id');
        }
        
        $gurus = User::where('role', 'guru')->get();

        return view('kelas.manage_mapel', compact('kelas', 'available_mapels', 'assignments', 'gurus', 'activePeriode'));
    }

    public function updateMapel(Request $request, Kelas $kelas)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif (Tahun Ajaran). Silahkan aktifkan periode terlebih dahulu.');
        }

        $request->validate([
            'mapel_ids' => 'array',
            'meta' => 'array',
        ]);
        
        $mapel_ids = $request->input('mapel_ids', []); // Array of checked mapel IDs
        $meta = $request->input('meta', []); // meta[mapel_id][guru_id] etc
        
        // Use Transaction
        \DB::transaction(function() use ($kelas, $activePeriode, $mapel_ids, $meta) {
            // 1. Remove assignments for this period that are NOT in the checked list
            // This handles "unchecking" a subject
            KelasMapel::where('kelas_id', $kelas->id)
                ->where('periode_id', $activePeriode->id)
                ->whereNotIn('mapel_id', $mapel_ids)
                ->delete();

            // 2. Update or Create for checked ones
            foreach($mapel_ids as $mapel_id) {
                $guru_id = $meta[$mapel_id]['guru_id'] ?? null;
                $kkm = $meta[$mapel_id]['kkm'] ?? 65; // Default KKM

                KelasMapel::updateOrCreate(
                    [
                        'kelas_id' => $kelas->id, 
                        'mapel_id' => $mapel_id,
                        'periode_id' => $activePeriode->id
                    ],
                    [
                        'guru_id' => $guru_id,
                        'kkm' => $kkm
                    ]
                );
            }
        });

        return redirect()->route('kelas.index')->with('success', 'Pengaturan Pengajar berhasil disimpan untuk Periode ' . $activePeriode->nama_periode);
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}
