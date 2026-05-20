@extends('layouts.app')

@section('header', 'Master Data Mapel')

@section('content')
@php
    $canManageMapel = auth()->user()->role === 'super_admin';
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Mata Pelajaran</h3>
        @if($canManageMapel)
            <a href="{{ route('mapel.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Mapel
            </a>
        @endif
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Mapel</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Tingkatan</th>
                    <th class="px-6 py-4">Bobot (H/U)</th>
                    @if($canManageMapel)
                        <th class="px-6 py-4 text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($mapels as $mapel)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $mapel->nama_mapel }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-md text-xs font-bold
                            {{ $mapel->kategori == 'umum' ? 'bg-blue-100 text-blue-700' : 
                               ($mapel->kategori == 'khusus' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                            {{ strtoupper($mapel->kategori) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $mapel->tingkatan }}</td>
                    <td class="px-6 py-4">{{ $mapel->bobot_harian }}% / {{ $mapel->bobot_ujian }}%</td>
                    @if($canManageMapel)
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('mapel.edit', $mapel) }}" class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" onsubmit="return confirm('Yakin hapus mapel ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    @endif
                </tr>
                @endforeach
                @if($mapels->isEmpty())
                <tr>
                    <td colspan="{{ $canManageMapel ? 5 : 4 }}" class="px-6 py-8 text-center text-slate-400">Belum ada data mapel.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        @foreach($mapels as $mapel)
        <div class="p-4 hover:bg-slate-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">{{ $mapel->nama_mapel }}</h4>
                    <p class="text-xs text-slate-500 mt-1 capitalize">Tingkatan: {{ $mapel->tingkatan }}</p>
                </div>
                <span class="px-2 py-1 rounded-md text-xs font-bold
                    {{ $mapel->kategori == 'umum' ? 'bg-blue-100 text-blue-700' : 
                       ($mapel->kategori == 'khusus' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700') }}">
                    {{ strtoupper($mapel->kategori) }}
                </span>
            </div>
            <div class="mb-3">
                <p class="text-sm text-slate-600"><span class="font-medium">Bobot:</span> Harian {{ $mapel->bobot_harian }}% / Ujian {{ $mapel->bobot_ujian }}%</p>
            </div>
            @if($canManageMapel)
                <div class="flex gap-2">
                    <a href="{{ route('mapel.edit', $mapel) }}" class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium text-center">
                        Edit
                    </a>
                    <form action="{{ route('mapel.destroy', $mapel) }}" method="POST" onsubmit="return confirm('Yakin hapus mapel ini?');" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                            Hapus
                        </button>
                    </form>
                </div>
            @endif
        </div>
        @endforeach
        @if($mapels->isEmpty())
        <div class="p-8 text-center text-slate-400">Belum ada data mapel.</div>
        @endif
    </div>
</div>
@endsection
