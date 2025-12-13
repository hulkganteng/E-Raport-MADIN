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
        $request->validate([
            'nama_mapel' => 'required',
            'kategori' => 'required|in:umum,khusus,cabang',
            'tingkatan' => 'required|in:ula,wustho,all',
            'bobot_harian' => 'required|integer|min:0|max:100',
            'bobot_ujian' => 'required|integer|min:0|max:100',
        ]);

        Mapel::create($request->all());

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
         $request->validate([
            'nama_mapel' => 'required',
            'kategori' => 'required|in:umum,khusus,cabang',
            'tingkatan' => 'required|in:ula,wustho,all',
            'bobot_harian' => 'required|integer|min:0|max:100',
            'bobot_ujian' => 'required|integer|min:0|max:100',
        ]);

        $mapel->update($request->all());

        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil diupdate');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mata Pelajaran berhasil dihapus');
    }
}
