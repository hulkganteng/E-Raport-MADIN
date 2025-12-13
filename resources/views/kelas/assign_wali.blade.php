@extends('layouts.app')

@section('header', 'Assign Wali Kelas')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('kelas.manage_wali') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-teal-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 bg-slate-50">
            <h3 class="text-xl font-bold text-slate-800">Assign Wali Kelas</h3>
            <p class="text-slate-500 mt-1">Periode Aktif: <span class="font-medium text-teal-600">{{ $activePeriode->nama_periode }} ({{ $activePeriode->semester }})</span></p>
        </div>

        <form action="{{ route('kelas.update_wali_single', $kelas->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Class Info Card -->
                <div class="flex items-center p-4 bg-teal-50 border border-teal-100 rounded-xl">
                    <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-lg font-bold text-slate-800">{{ $kelas->nama_kelas }}</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kelas->tingkatan == 'ula' ? 'bg-emerald-100 text-emerald-800' : 'bg-indigo-100 text-indigo-800' }}">
                            {{ strtoupper($kelas->tingkatan) }}
                        </span>
                    </div>
                </div>

                <!-- Selection Input -->
                <div>
                    <label for="user_id" class="block text-sm font-medium text-slate-700 mb-2">Pilih Guru / Wali Kelas</label>
                    <div class="relative">
                        <select name="user_id" id="user_id" class="block w-full py-3 pl-4 pr-10 text-slate-800 bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none transition shadow-sm">
                            <option value="">-- Kosongkan (Belum Ada) --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ ($currentAssignment && $currentAssignment->user_id == $guru->id) ? 'selected' : '' }}>
                                    {{ $guru->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 mt-2">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Hanya guru yang belum menjadi wali kelas di kelas lain yang ditampilkan.
                    </p>
                </div>

                <!-- Actions -->
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('kelas.manage_wali') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition font-medium">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-bold shadow-lg shadow-teal-500/30">
                        Simpan Penugasan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
