@extends('layouts.app')

@section('header', 'Manajemen Periode')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Periode Akademik</h3>
        <a href="{{ route('periode.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Periode
        </a>
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Periode</th>
                    <th class="px-6 py-4">Mulai</th>
                    <th class="px-6 py-4">Selesai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($periodes as $periode)
                <tr class="hover:bg-slate-50 transition {{ $periode->is_active ? 'bg-green-50/50' : '' }}">
                    <td class="px-6 py-4 font-medium text-slate-800">
                        {{ $periode->nama_periode }}
                        <span class="block text-xs {{ $periode->semester == 'ganjil' ? 'text-orange-600' : 'text-blue-600' }} uppercase mt-1">{{ $periode->semester }}</span>
                    </td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periode->start_date)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($periode->end_date)->translatedFormat('d M Y') }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($periode->is_active)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <form action="{{ route('periode.activate', $periode->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-xs text-slate-400 hover:text-teal-600 underline">
                                    Set Aktif
                                </button>
                            </form>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('periode.edit', $periode->id) }}" class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" onsubmit="return confirm('Hapus periode ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed" {{ $periode->is_active ? 'disabled title="Tidak bisa hapus periode aktif"' : '' }}>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        @foreach($periodes as $periode)
        <div class="p-4 hover:bg-slate-50 transition {{ $periode->is_active ? 'bg-green-50' : '' }}">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">{{ $periode->nama_periode }}</h4>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $periode->semester == 'ganjil' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700' }} uppercase mt-1">
                        {{ $periode->semester }}
                    </span>
                </div>
                @if($periode->is_active)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Aktif
                </span>
                @else
                <form action="{{ route('periode.activate', $periode->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-2 py-1 text-xs bg-slate-100 text-slate-600 rounded hover:bg-slate-200 transition">
                        Set Aktif
                    </button>
                </form>
                @endif
            </div>
            <div class="text-sm text-slate-600 space-y-1 mb-3">
                <p><span class="font-medium">Mulai:</span> {{ \Carbon\Carbon::parse($periode->start_date)->translatedFormat('d M Y') }}</p>
                <p><span class="font-medium">Selesai:</span> {{ \Carbon\Carbon::parse($periode->end_date)->translatedFormat('d M Y') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('periode.edit', $periode->id) }}" class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium text-center">
                    Edit
                </a>
                <form action="{{ route('periode.destroy', $periode->id) }}" method="POST" onsubmit="return confirm('Hapus periode ini?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium disabled:opacity-50 disabled:cursor-not-allowed" {{ $periode->is_active ? 'disabled' : '' }}>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
