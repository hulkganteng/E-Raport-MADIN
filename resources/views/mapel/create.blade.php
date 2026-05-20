@extends('layouts.app')

@section('header', 'Tambah Mapel')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('mapel.store') }}" method="POST" class="space-y-6">
        @csrf
        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div>
            <label class="block text-sm font-medium text-slate-700">Nama Mata Pelajaran</label>
            <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: Fiqih">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Kategori</label>
                <select name="kategori" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="umum" {{ old('kategori') == 'umum' ? 'selected' : '' }}>Umum</option>
                    <option value="khusus" {{ old('kategori') == 'khusus' ? 'selected' : '' }}>Khusus</option>
                    <option value="cabang" {{ old('kategori') == 'cabang' ? 'selected' : '' }}>Cabang</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkatan</label>
                <select name="tingkatan" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="all" {{ old('tingkatan', 'all') == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="ula" {{ old('tingkatan') == 'ula' ? 'selected' : '' }}>Ula (1-3)</option>
                    <option value="wustho" {{ old('tingkatan') == 'wustho' ? 'selected' : '' }}>Wustho (4-6)</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Bobot Harian (%)</label>
                <input type="number" name="bobot_harian" value="{{ old('bobot_harian', 50) }}" min="0" max="100" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Bobot Ujian (%)</label>
                <input type="number" name="bobot_ujian" value="{{ old('bobot_ujian', 50) }}" min="0" max="100" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('mapel.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Simpan Mapel</button>
        </div>
    </form>
</div>
@endsection
