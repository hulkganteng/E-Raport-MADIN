@extends('layouts.app')

@section('header', 'Edit Kelas')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('kelas.update', $kelas) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-slate-700">Nama Kelas</label>
            <input type="text" name="nama_kelas" value="{{ $kelas->nama_kelas }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkatan</label>
                <select name="tingkatan" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    <option value="ula" {{ $kelas->tingkatan == 'ula' ? 'selected' : '' }}>Ula</option>
                    <option value="wustho" {{ $kelas->tingkatan == 'wustho' ? 'selected' : '' }}>Wustho</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tahun Ajar</label>
                <input type="text" name="tahun_ajar" value="{{ $kelas->tahun_ajar }}" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700">Wali Kelas</label>
            <select name="wali_kelas_id" class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <option value="">-- Pilih Wali Kelas --</option>
                @foreach($wali_kelas as $guru)
                    <option value="{{ $guru->id }}" {{ $kelas->wali_kelas_id == $guru->id ? 'selected' : '' }}>{{ $guru->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('kelas.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Update Kelas</button>
        </div>
    </form>
</div>
@endsection
