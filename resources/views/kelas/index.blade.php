@extends('layouts.app')

@section('header', 'Manajemen Kelas')

@section('content')
@php
    $canManageKelas = auth()->user()->role === 'super_admin';
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Kelas</h3>
        @if($canManageKelas)
            <a href="{{ route('kelas.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kelas
            </a>
        @endif
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Kelas</th>
                    <th class="px-6 py-4">Tingkatan</th>
                    <th class="px-6 py-4">Wali Kelas</th>
                    <th class="px-6 py-4">Tahun Ajar</th>
                    <th class="px-6 py-4 text-center">Mapel</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($kelas as $k)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $k->nama_kelas }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold {{ $k->tingkatan == 'ula' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($k->tingkatan) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $k->current_wali_kelas->user->name ?? '-' }}</td>
                    <td class="px-6 py-4">{{ $k->tahun_ajar }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                            {{ $k->kelas_mapel->count() }} Mapel
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            @if($canManageKelas)
                                <a href="{{ route('kelas.manage_mapel', $k) }}" class="p-2 bg-indigo-100 text-indigo-600 rounded-lg hover:bg-indigo-200 transition" title="Atur Mapel">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                </a>
                            @endif
                            <a href="{{ route('rekap.index', $k->id) }}" class="p-2 bg-teal-100 text-teal-600 rounded-lg hover:bg-teal-200 transition" title="Rekap Nilai">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </a>
                            @if($canManageKelas)
                                <a href="{{ route('kelas.edit', $k) }}" class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('kelas.destroy', $k) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($kelas->isEmpty())
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-400">Belum ada data kelas.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="lg:hidden divide-y divide-slate-100">
        @foreach($kelas as $k)
        <div class="p-4 hover:bg-slate-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800 text-base">{{ $k->nama_kelas }}</h4>
                    <p class="text-xs text-slate-500 mt-1">Tahun Ajar: {{ $k->tahun_ajar }}</p>
                </div>
                <span class="px-2 py-1 rounded text-xs font-bold {{ $k->tingkatan == 'ula' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($k->tingkatan) }}
                </span>
            </div>
            <div class="space-y-2 mb-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Wali Kelas:</span>
                    <span class="font-medium text-slate-700">{{ $k->current_wali_kelas->user->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Jumlah Mapel:</span>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                        {{ $k->kelas_mapel->count() }} Mapel
                    </span>
                </div>
            </div>
            <div class="grid {{ $canManageKelas ? 'grid-cols-2' : 'grid-cols-1' }} gap-2">
                @if($canManageKelas)
                    <a href="{{ route('kelas.manage_mapel', $k) }}" class="px-3 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition text-sm font-medium text-center">
                        Atur Mapel
                    </a>
                @endif
                <a href="{{ route('rekap.index', $k->id) }}" class="px-3 py-2 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition text-sm font-medium text-center">
                    Rekap
                </a>
                @if($canManageKelas)
                    <a href="{{ route('kelas.edit', $k) }}" class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium text-center">
                        Edit
                    </a>
                    <form action="{{ route('kelas.destroy', $k) }}" method="POST" onsubmit="return confirm('Yakin hapus kelas ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                            Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @endforeach
        @if($kelas->isEmpty())
        <div class="p-8 text-center text-slate-400">Belum ada data kelas.</div>
        @endif
    </div>
</div>
@endsection
