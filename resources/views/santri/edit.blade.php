@extends('layouts.app')

@section('header', 'Edit Data Santri')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('santri.update', $santri) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column: Academic Info -->
            <div class="space-y-6">
                <h4 class="font-bold text-teal-700 border-b border-teal-100 pb-2">Data Akademik</h4>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nomor Induk (NIS)</label>
                    <input type="text" name="nis" value="{{ $santri->nis }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ $santri->nama_lengkap }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">Kelas</label>
                    <select name="kelas_id" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                         @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $santri->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }} ({{ ucfirst($k->tingkatan) }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                        <option value="aktif" {{ $santri->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="lulus" {{ $santri->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="pindah" {{ $santri->status == 'pindah' ? 'selected' : '' }}>Pindah/Keluar</option>
                    </select>
                </div>
            </div>

            <!-- Right Column: Personal & Biodata -->
            <div class="space-y-6">
                 <h4 class="font-bold text-teal-700 border-b border-teal-100 pb-2">Biodata Pribadi</h4>
                 
                 <div class="grid grid-cols-2 gap-4">
                     <div>
                        <label class="block text-sm font-medium text-slate-700">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ $santri->biodata->tempat_lahir ?? '' }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                     </div>
                     <div>
                        <label class="block text-sm font-medium text-slate-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ $santri->biodata->tanggal_lahir ?? '' }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                     </div>
                 </div>
                 
                 <div>
                    <label class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">{{ $santri->biodata->alamat ?? '' }}</textarea>
                </div>
                
                <h4 class="font-bold text-teal-700 border-b border-teal-100 pb-2 mt-6">Data Orang Tua</h4>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Ayah</label>
                        <input type="text" name="nama_ayah" value="{{ $santri->biodata->nama_ayah ?? '' }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-slate-700">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ $santri->biodata->pekerjaan_ayah ?? '' }}" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nama Ibu</label>
                        <input type="text" name="nama_ibu" value="{{ $santri->biodata->nama_ibu ?? '' }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                    <div>
                         <label class="block text-sm font-medium text-slate-700">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ $santri->biodata->pekerjaan_ibu ?? '' }}" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    </div>
                </div>
                
                <div>
                     <label class="block text-sm font-medium text-slate-700">No HP Orang Tua</label>
                     <input type="text" name="no_hp_ortu" value="{{ $santri->biodata->no_hp_ortu ?? '' }}" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                </div>

            </div>
        </div>

        <div class="flex justify-end gap-3 pt-8 mt-4 border-t border-slate-100">
            <a href="{{ route('santri.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Update Data Santri</button>
        </div>
    </form>
</div>
@endsection
