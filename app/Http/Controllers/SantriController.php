<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with('kelas');
        
        if ($request->has('kelas_id') && $request->kelas_id != '') {
            $query->where('kelas_id', $request->kelas_id);
        }

        $santris = $query->paginate(20);
        $kelas = Kelas::all();
        
        return view('santri.index', compact('santris', 'kelas'));
    }

    public function show(Santri $santri)
    {
        $santri->load('kelas');
        $kelas = Kelas::all();
        // Since we don't have a show view yet, maybe redirect to edit or rekap?
        // Let's redirect to edit if admin, or maybe just return a simple view or json?
        // User didn't ask for specialized show, but let's provide basic or rekap
        // Actually, let's redirect to rekap? Or just return the model
        // For debugging, let's just return view('santri.edit') generally readonly?
        // Let's keep it simple:
        return view('santri.edit', compact('santri', 'kelas'));
        // Or if edit is blocked for non-admin, this might be issue.
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('santri.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            // Main Data
            'nis' => 'required|string|max:50|unique:santri,nis',
            'nama_lengkap' => 'required|string|max:120',
            'kelas_id' => 'required|exists:kelas,id',
            
            // Biodata
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:1000',
            'nama_ayah' => 'required|string|max:120',
            'pekerjaan_ayah' => 'nullable|string|max:120',
            'nama_ibu' => 'required|string|max:120',
            'pekerjaan_ibu' => 'nullable|string|max:120',
            'no_hp_ortu' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $santri = Santri::create([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas_id' => $request->kelas_id,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'status' => 'aktif',
            ]);

            DB::commit();
            return redirect()->route('santri.index')->with('success', 'Data Santri berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Santri $santri)
    {
        $kelas = Kelas::all();
        return view('santri.edit', compact('santri', 'kelas'));
    }

    public function update(Request $request, Santri $santri)
    {
        $request->validate([
             // Main Data
            'nis' => 'required|string|max:50|unique:santri,nis,' . $santri->id,
            'nama_lengkap' => 'required|string|max:120',
            'kelas_id' => 'required|exists:kelas,id',
            'status' => 'required|in:aktif,lulus,pindah',
             // Biodata
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:1000',
            'nama_ayah' => 'required|string|max:120',
            'pekerjaan_ayah' => 'nullable|string|max:120',
            'nama_ibu' => 'required|string|max:120',
            'pekerjaan_ibu' => 'nullable|string|max:120',
            'no_hp_ortu' => 'nullable|string|max:30',
        ]);

        try {
            DB::beginTransaction();

            $santri->update([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas_id' => $request->kelas_id,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
                'status' => $request->status ?? 'aktif',
            ]);

            DB::commit();
            return redirect()->route('santri.index')->with('success', 'Data Santri berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update gagal: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Santri $santri)
    {
        $santri->delete();
        return redirect()->route('santri.index')->with('success', 'Santri berhasil dihapus');
    }
}
