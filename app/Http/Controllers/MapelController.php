<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapels = Mapel::all();
        return view('mapel.index', compact('mapels'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_mapel' => 'required',
            'kategori' => 'required|in:umum,khusus,cabang',
            'tingkatan' => 'required|in:ula,wustho,all',
            'bobot_harian' => 'required|integer|min:0|max:100',
            'bobot_ujian' => 'required|integer|min:0|max:100',
        ]);

        if ((int) $data['bobot_harian'] + (int) $data['bobot_ujian'] !== 100) {
            return back()->withErrors(['bobot_harian' => 'Total bobot harian dan ujian harus 100%.'])->withInput();
        }

        Mapel::create($data);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
         $data = $request->validate([
            'nama_mapel' => 'required',
            'kategori' => 'required|in:umum,khusus,cabang',
            'tingkatan' => 'required|in:ula,wustho,all',
            'bobot_harian' => 'required|integer|min:0|max:100',
            'bobot_ujian' => 'required|integer|min:0|max:100',
        ]);

        if ((int) $data['bobot_harian'] + (int) $data['bobot_ujian'] !== 100) {
            return back()->withErrors(['bobot_harian' => 'Total bobot harian dan ujian harus 100%.'])->withInput();
        }

        $mapel->update($data);

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil diupdate');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus');
    }
}
