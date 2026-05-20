@extends('layouts.app')

@section('header', 'Kenaikan Kelas & Kelulusan')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kelas</h3>
        <p class="text-sm text-slate-500 mt-1">Pilih kelas untuk memproses kenaikan atau kelulusan santri.</p>
    </div>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4">Tingkatan</th>
                    <th class="px-6 py-4 text-center">Jumlah Santri Aktif</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kelasList as $kelas)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $kelas->nama_kelas }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $kelas->tingkatan == 'ula' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($kelas->tingkatan) }} - {{ $kelas->tingkat }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            {{ $kelas->santri_count }} Santri
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('kenaikan.show', $kelas->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-teal-600 text-white text-xs rounded-lg hover:bg-teal-700 transition shadow-sm font-semibold">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Proses
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($kelasList->isEmpty())
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">Belum ada data kelas.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        @foreach($kelasList as $kelas)
        <div class="p-4 hover:bg-slate-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">{{ $kelas->nama_kelas }}</h4>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-bold {{ $kelas->tingkatan == 'ula' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($kelas->tingkatan) }} - {{ $kelas->tingkat }}
                    </span>
                </div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                    {{ $kelas->santri_count }} Santri
                </span>
            </div>
            <a href="{{ route('kenaikan.show', $kelas->id) }}" class="block text-center px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30">
                Proses Kenaikan
            </a>
        </div>
        @endforeach
        @if($kelasList->isEmpty())
        <div class="p-8 text-center text-slate-400">Belum ada data kelas.</div>
        @endif
    </div>
</div>
@endsection
