<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::orderBy('is_active', 'desc')->orderBy('start_date', 'desc')->get();
        return view('periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        DB::transaction(function () use ($request) {
            if ($request->has('is_active') && $request->is_active) {
                // Deactivate all others
                Periode::where('is_active', true)->update(['is_active' => false]);
            }
            
            // If this is the FIRST period, make it active by default if not specified
            $count = Periode::count();
            $isActive = $request->is_active || $count === 0;

            Periode::create([
                'nama_periode' => $request->nama_periode,
                'semester' => $request->semester,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $isActive
            ]);
        });

        return redirect()->route('periode.index')->with('success', 'Periode berhasil ditambahkan');
    }

    public function edit(Periode $periode)
    {
        return view('periode.edit', compact('periode'));
    }

    public function update(Request $request, Periode $periode)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'semester' => 'required|in:ganjil,genap',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'boolean'
        ]);

        DB::transaction(function () use ($request, $periode) {
            if ($request->has('is_active') && $request->is_active) {
                 // Deactivate all others
                 Periode::where('id', '!=', $periode->id)->update(['is_active' => false]);
            }

            $periode->update([
                'nama_periode' => $request->nama_periode,
                'semester' => $request->semester,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => $request->is_active ?? $periode->is_active
            ]);
        });

        return redirect()->route('periode.index')->with('success', 'Periode berhasil diperbarui');
    }

    public function destroy(Periode $periode)
    {
        if ($periode->is_active) {
            return back()->with('error', 'Tidak bisa menghapus periode yang sedang aktif. Aktifkan periode lain terlebih dahulu.');
        }
        $periode->delete();
        return redirect()->route('periode.index')->with('success', 'Periode berhasil dihapus');
    }
    
    public function activate(Periode $periode)
    {
        DB::transaction(function () use ($periode) {
            Periode::where('id', '!=', $periode->id)->update(['is_active' => false]);
            $periode->update(['is_active' => true]);
        });
        
        return back()->with('success', 'Periode ' . $periode->nama_periode . ' telah diaktifkan.');
    }
}
