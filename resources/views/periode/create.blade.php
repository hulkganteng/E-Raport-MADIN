@extends('layouts.app')

@section('header', 'Tambah Periode Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
    <form action="{{ route('periode.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Periode</label>
            <input type="text" name="nama_periode" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Contoh: Semester Ganjil 2024/2025" required>
        </div>

        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Semester</label>
            <select name="semester" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="ganjil">Ganjil</option>
                <option value="genap">Genap</option>
            </select>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai</label>
                <input type="date" name="start_date" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Selesai</label>
                <input type="date" name="end_date" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500" required>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded text-teal-600 focus:ring-teal-500">
            <label for="is_active" class="text-sm text-slate-700">Set sebagai periode aktif sekarang</label>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('periode.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition font-medium">Batal</a>
            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-bold shadow-lg shadow-teal-500/30">
                Simpan Periode
            </button>
        </div>
    </form>
</div>
@endsection
