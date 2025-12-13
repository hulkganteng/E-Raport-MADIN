<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Kelas;
use App\Models\SantriBiodata;
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
        $santri->load('biodata', 'kelas');
        // Since we don't have a show view yet, maybe redirect to edit or rekap?
        // Let's redirect to edit if admin, or maybe just return a simple view or json?
        // User didn't ask for specialized show, but let's provide basic or rekap
        // Actually, let's redirect to rekap? Or just return the model
        // For debugging, let's just return view('santri.edit') generally readonly?
        // Let's keep it simple:
        return view('santri.edit', compact('santri'));
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
            'nis' => 'required|unique:santri,nis',
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            
            // Biodata
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $santri = Santri::create([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas_id' => $request->kelas_id,
                'status' => 'aktif',
            ]);

            SantriBiodata::create([
                'santri_id' => $santri->id,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
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
        $santri->load('biodata');
        $kelas = Kelas::all();
        return view('santri.edit', compact('santri', 'kelas'));
    }

    public function update(Request $request, Santri $santri)
    {
        $request->validate([
             // Main Data
            'nis' => 'required|unique:santri,nis,' . $santri->id,
            'nama_lengkap' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
             // Biodata
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
             'alamat' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $santri->update([
                'nis' => $request->nis,
                'nama_lengkap' => $request->nama_lengkap,
                'kelas_id' => $request->kelas_id,
                'status' => $request->status ?? 'aktif',
            ]);

            $santri->biodata()->updateOrCreate(
                ['santri_id' => $santri->id],
                [
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'alamat' => $request->alamat,
                    'nama_ayah' => $request->nama_ayah,
                    'pekerjaan_ayah' => $request->pekerjaan_ayah,
                    'nama_ibu' => $request->nama_ibu,
                    'pekerjaan_ibu' => $request->pekerjaan_ibu,
                    'no_hp_ortu' => $request->no_hp_ortu,
                ]
            );

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
