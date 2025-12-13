@extends('layouts.app')

@section('header', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('kelas.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700">Nama Kelas</label>
            <input type="text" name="nama_kelas" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: 1 Ula A">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkatan</label>
                <select name="tingkatan" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="ula">Ula</option>
                    <option value="wustho">Wustho</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tahun Ajar</label>
                <input type="text" name="tahun_ajar" value="{{ date('Y') }}/{{ date('Y')+1 }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Wali Kelas</label>
            <select name="wali_kelas_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">-- Pilih Wali Kelas --</option>
                @foreach($wali_kelas as $guru)
                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500">*User dengan role 'Wali Kelas'</p>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('kelas.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Simpan Kelas</button>
        </div>
    </form>
</div>
@endsection
