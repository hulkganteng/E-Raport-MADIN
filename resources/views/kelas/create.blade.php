@extends('layouts.app')

@section('header', 'Tambah Kelas Baru')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('kelas.store') }}" method="POST" class="space-y-6">
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
            <label class="block text-sm font-medium text-slate-700">Nama Kelas</label>
            <input type="text" name="nama_kelas" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm" placeholder="Contoh: 1 A">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkat Kelas</label>
                <select name="tingkat" id="tingkat" required class="mt-1 block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                    @for($i = 1; $i <= 6; $i++)
                        <option value="{{ $i }}" {{ old('tingkat', 1) == $i ? 'selected' : '' }}>Kelas {{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tingkatan</label>
                <div id="tingkatan-display" class="mt-1 block w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-100 text-slate-700 sm:text-sm">
                    Ula
                </div>
                <p class="mt-1 text-xs text-slate-500">Otomatis dari tingkat kelas.</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Tahun Ajar</label>
                <div class="mt-1 block w-full px-4 py-3 rounded-lg border border-slate-300 bg-slate-100 text-slate-700 sm:text-sm">
                    {{ $activePeriode->nama_periode ?? 'Belum ada tahun ajaran aktif' }}
                </div>
                <p class="mt-1 text-xs text-slate-500">Mengikuti Tahun Ajaran aktif.</p>
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
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30 disabled:opacity-50 disabled:cursor-not-allowed" {{ !$activePeriode ? 'disabled' : '' }}>Simpan Kelas</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tingkatSelect = document.getElementById('tingkat');
        const tingkatanDisplay = document.getElementById('tingkatan-display');

        function updateTingkatan() {
            const tingkat = parseInt(tingkatSelect.value, 10);
            tingkatanDisplay.textContent = tingkat <= 3 ? 'Ula' : 'Wustho';
        }

        tingkatSelect.addEventListener('change', updateTingkatan);
        updateTingkatan();
    });
</script>
@endsection
