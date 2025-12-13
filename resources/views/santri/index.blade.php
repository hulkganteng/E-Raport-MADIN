@extends('layouts.app')

@section('header', 'Data Santri')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col gap-4">
        <h3 class="text-lg font-bold text-slate-800">Direktori Santri</h3>
        
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <form action="{{ route('santri.index') }}" method="GET" class="flex-1">
                <select name="kelas_id" onchange="this.form.submit()" class="w-full text-sm rounded-lg border-slate-300 focus:border-teal-500 focus:ring-teal-500 py-2">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </form>
            
            <a href="{{ route('santri.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30 flex items-center justify-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="hidden sm:inline">Tambah Santri</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">NIS</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Kelas</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($santris as $santri)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-mono text-xs">{{ $santri->nis }}</td>
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $santri->nama_lengkap }}</td>
                    <td class="px-6 py-4">{{ $santri->kelas->nama_kelas ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                            {{ ucfirst($santri->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('santri.edit', $santri) }}" class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            <form action="{{ route('santri.destroy', $santri) }}" method="POST" onsubmit="return confirm('Yakin hapus santri ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @if($santris->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada data santri.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        @foreach($santris as $santri)
        <div class="p-4 hover:bg-slate-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">{{ $santri->nama_lengkap }}</h4>
                    <p class="text-xs text-slate-500 font-mono mt-1">NIS: {{ $santri->nis }}</p>
                </div>
                <span class="inline-flex px-2 text-xs font-semibold leading-5 text-green-800 bg-green-100 rounded-full">
                    {{ ucfirst($santri->status) }}
                </span>
            </div>
            <div class="mb-3">
                <p class="text-sm text-slate-600"><span class="font-medium">Kelas:</span> {{ $santri->kelas->nama_kelas ?? '-' }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('santri.edit', $santri) }}" class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium text-center">
                    Edit
                </a>
                <form action="{{ route('santri.destroy', $santri) }}" method="POST" onsubmit="return confirm('Yakin hapus santri ini?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
        @if($santris->isEmpty())
        <div class="p-8 text-center text-slate-400">Belum ada data santri.</div>
        @endif
    </div>
    
    <div class="px-4 sm:px-6 py-4">
        {{ $santris->links() }}
    </div>
</div>
@endsection
