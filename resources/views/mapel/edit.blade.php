@extends('layouts.app')

@section('header', 'Edit Mapel')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('mapel.update', $mapel) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700">Nama Mata Pelajaran</label>
            <input type="text" name="nama_mapel" value="{{ $mapel->nama_mapel }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Kategori</label>
                <select name="kategori" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="umum" {{ $mapel->kategori == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="khusus" {{ $mapel->kategori == 'khusus' ? 'selected' : '' }}>Khusus</option>
                    <option value="cabang" {{ $mapel->kategori == 'cabang' ? 'selected' : '' }}>Cabang</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkatan</label>
                <select name="tingkatan" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="all" {{ $mapel->tingkatan == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="ula" {{ $mapel->tingkatan == 'ula' ? 'selected' : '' }}>Ula</option>
                    <option value="wustho" {{ $mapel->tingkatan == 'wustho' ? 'selected' : '' }}>Wustho</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bobot Harian (%)</label>
                <input type="number" name="bobot_harian" value="{{ $mapel->bobot_harian }}" min="0" max="100" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Bobot Ujian (%)</label>
                <input type="number" name="bobot_ujian" value="{{ $mapel->bobot_ujian }}" min="0" max="100" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('mapel.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Update Mapel</button>
        </div>
    </form>
</div>
@endsection
