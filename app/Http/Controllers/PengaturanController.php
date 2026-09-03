<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravolt\Indonesia\Models\Province;

class PengaturanController extends Controller
{
    /**
     * Show the institution settings form.
     */
    public function edit()
    {
        $settings = Lembaga::main();
        $provinces = Province::query()->orderBy('name')->get(['code', 'name']);

        return view('pengaturan.edit', compact('settings', 'provinces'));
    }

    /**
     * Update the institution settings, including optional logo upload.
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama_lembaga' => 'nullable|string|max:255',
            'jenjang' => 'nullable|string|max:255',
            'nama_sekolah' => 'nullable|string|max:255',
            'npsn' => 'nullable|string|max:50',
            'nsm' => 'nullable|string|max:50',
            'nss' => 'nullable|string|max:50',
            'alamat' => 'nullable|string|max:255',
            'desa' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kabupaten' => 'nullable|string|max:255',
            'provinsi' => 'nullable|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'nama_kepala' => 'nullable|string|max:255',
            'nip_kepala' => 'nullable|string|max:255',
            'kkm_default' => 'required|numeric|min:0|max:100',
            'grade_min_a' => 'required|numeric|min:0|max:100',
            'grade_min_b' => 'required|numeric|min:0|max:100',
            'grade_min_c' => 'required|numeric|min:0|max:100',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $settings = Lembaga::main();

        $data = array_intersect_key($request->only([...$settings->fillable]), array_flip([
            'nama_lembaga', 'jenjang', 'nama_sekolah', 'npsn', 'nsm', 'nss',
            'alamat', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos',
            'telepon', 'email', 'website', 'nama_kepala', 'nip_kepala',
            'kkm_default', 'grade_min_a', 'grade_min_b', 'grade_min_c',
        ]));

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            if ($path !== false) {
                // Hapus logo lama (selain default bawaan)
                $old = $settings->logo;
                if ($old && is_string($old) && Storage::disk('public')->exists($old)) {
                    Storage::disk('public')->delete($old);
                }
                $data['logo'] = $path;
            }
        }

        $settings->update($data);
        Lembaga::flushCache();

        return back()->with('success', 'Pengaturan lembaga berhasil disimpan.');
    }
}
