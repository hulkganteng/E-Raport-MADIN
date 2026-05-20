@extends('layouts.app')

@section('header', 'Tambah Periode Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <form action="{{ route('periode.store') }}" method="POST" class="space-y-6">
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
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Periode</label>
            <input type="text" name="nama_periode" value="{{ old('nama_periode') }}" class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-500 focus:ring-teal-500" placeholder="Contoh: Semester Ganjil 2024/2025" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Semester</label>
            <select name="semester" class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-500 focus:ring-teal-500">
                <option value="ganjil" {{ old('semester', 'ganjil') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="genap" {{ old('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kepala Madrasah</label>
            <input type="text" name="nama_kepala_madrasah" value="{{ old('nama_kepala_madrasah') }}" class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-500 focus:ring-teal-500" placeholder="Contoh: M. SHOLAHUDDIN MALIKI, S.Pd.I">
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-500 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-50 focus:outline-none focus:border-teal-500 focus:ring-teal-500" required>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active') ? 'checked' : '' }} class="rounded text-teal-600 focus:ring-teal-500">
            <label for="is_active" class="text-sm text-slate-700">Set sebagai periode aktif sekarang</label>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('periode.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">
                Simpan Periode
            </button>
        </div>
    </form>
</div>
@endsection
