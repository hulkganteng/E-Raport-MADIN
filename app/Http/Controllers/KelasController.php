<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\Mapel;
use App\Models\KelasMapel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

        
        $isAdmin = in_array($user->role, ['super_admin', 'admin', 'kepsek', 'staff']); // Adjust roles as needed

        if ($isAdmin) {
            $kelas = Kelas::with([
                'current_wali_kelas.user',
                'kelas_mapel' => function ($query) use ($activePeriode) {
                    if ($activePeriode) {
                        $query->where('periode_id', $activePeriode->id);
                    }
                },
            ])->get();
        } elseif ($activePeriode && $assignedKelasIds->isNotEmpty()) {
            $kelas = Kelas::whereIn('id', $assignedKelasIds)
                        ->with([
                            'current_wali_kelas.user',
                            'kelas_mapel' => fn ($query) => $query->where('periode_id', $activePeriode->id),
                        ])
                        ->get();
        } else {
            $kelas = collect();
        }

        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $wali_kelas = User::where('role', 'wali_kelas')->get();
        $activePeriode = Periode::where('is_active', true)->first();

        return view('kelas.create', compact('wali_kelas', 'activePeriode'));
    }

    public function store(Request $request)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif. Silahkan aktifkan Tahun Ajaran terlebih dahulu.')->withInput();
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|integer|min:1|max:6',
            'wali_kelas_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', 'wali_kelas'),
            ],
        ]);
        $tingkat = (int) $request->tingkat;

        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $tingkat,
            'tingkatan' => $this->resolveTingkatan($tingkat),
            'tahun_ajar' => $activePeriode->nama_periode,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kelas $kelas)
    {
        $wali_kelas = User::where('role', 'wali_kelas')->get();
        $activePeriode = Periode::where('is_active', true)->first();

        return view('kelas.edit', compact('kelas', 'wali_kelas', 'activePeriode'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $activePeriode = Periode::where('is_active', true)->first();
        if (!$activePeriode) {
            return back()->with('error', 'Tidak ada periode aktif. Silahkan aktifkan Tahun Ajaran terlebih dahulu.')->withInput();
        }

        $request->validate([
            'nama_kelas' => 'required|string|max:50',
            'tingkat' => 'required|integer|min:1|max:6',
            'wali_kelas_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', 'wali_kelas'),
            ],
        ]);
        $tingkat = (int) $request->tingkat;

        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $tingkat,
            'tingkatan' => $this->resolveTingkatan($tingkat),
            'tahun_ajar' => $activePeriode->nama_periode,
            'wali_kelas_id' => $request->wali_kelas_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diupdate');
    }

    private function resolveTingkatan(int $tingkat): string
    {
        return $tingkat <= 3 ? 'ula' : 'wustho';
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
            'user_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->whereIn('role', ['guru', 'wali_kelas'])),
            ],
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

    // Deprecated bulk update — redirect to manage page
    public function updateWali(Request $request)
    {
        return redirect()->route('kelas.manage_wali')->with('error', 'Gunakan halaman assign wali per kelas.');
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
            'mapel_ids' => 'nullable|array',
            'mapel_ids.*' => 'integer|distinct|exists:mapel,id',
            'meta' => 'nullable|array',
        ]);
        
        $mapel_ids = collect($request->input('mapel_ids', []))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
        $meta = $request->input('meta', []); // meta[mapel_id][guru_id] etc
        $availableMapelIds = Mapel::whereIn('tingkatan', [$kelas->tingkatan, 'all'])
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $validGuruIds = User::where('role', 'guru')
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
        $errors = [];
        $assignments = [];

        foreach ($mapel_ids as $mapel_id) {
            if (!in_array($mapel_id, $availableMapelIds, true)) {
                $errors["mapel_ids.$mapel_id"] = 'Mata pelajaran yang dipilih tidak sesuai tingkatan kelas ini.';
                continue;
            }

            $guru_id = $meta[$mapel_id]['guru_id'] ?? null;
            $kkm = $meta[$mapel_id]['kkm'] ?? null;

            if ($guru_id === null || $guru_id === '') {
                $errors["meta.$mapel_id.guru_id"] = 'Guru pengampu wajib dipilih untuk setiap mapel yang dicentang.';
            } elseif (!in_array((int) $guru_id, $validGuruIds, true)) {
                $errors["meta.$mapel_id.guru_id"] = 'Guru pengampu tidak valid atau bukan user dengan role guru.';
            }

            if ($kkm === null || $kkm === '') {
                $errors["meta.$mapel_id.kkm"] = 'KKM wajib diisi untuk setiap mapel yang dicentang.';
            } elseif (!is_numeric($kkm) || (float) $kkm < 0 || (float) $kkm > 100) {
                $errors["meta.$mapel_id.kkm"] = 'KKM harus berupa angka 0-100.';
            }

            if (!isset($errors["meta.$mapel_id.guru_id"]) && !isset($errors["meta.$mapel_id.kkm"])) {
                $assignments[$mapel_id] = [
                    'guru_id' => (int) $guru_id,
                    'kkm' => (float) $kkm,
                ];
            }
        }

        if (!empty($errors)) {
            return back()->withErrors($errors)->withInput();
        }
        
        // Use Transaction
        \DB::transaction(function() use ($kelas, $activePeriode, $mapel_ids, $assignments) {
            // 1. Remove assignments for this period that are NOT in the checked list
            // This handles "unchecking" a subject
            KelasMapel::where('kelas_id', $kelas->id)
                ->where('periode_id', $activePeriode->id)
                ->whereNotIn('mapel_id', $mapel_ids)
                ->delete();

            // 2. Update or Create for checked ones
            foreach($mapel_ids as $mapel_id) {
                $assignment = $assignments[$mapel_id];

                KelasMapel::updateOrCreate(
                    [
                        'kelas_id' => $kelas->id, 
                        'mapel_id' => $mapel_id,
                        'periode_id' => $activePeriode->id
                    ],
                    [
                        'guru_id' => $assignment['guru_id'],
                        'kkm' => $assignment['kkm'],
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
