@extends('layouts.app')

@section('header', 'Atur Wali Kelas')

@section('content')
<div class="max-w-5xl mx-auto">
    
    @if(!$activePeriode)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    Tidak ada Periode (Tahun Ajaran) yang aktif. Silahkan aktifkan periode terlebih dahulu.
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-blue-700 font-medium">
                    Konfigurasi Wali Kelas untuk Periode: {{ $activePeriode->nama_periode }} ({{ $activePeriode->semester }})
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Daftar Kelas & Wali</h3>
                <p class="text-sm text-slate-500 mt-1">Pilih kelas untuk mengatur Wali Kelas</p>
            </div>
            <a href="{{ route('kelas.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition text-sm font-semibold">
                Kembali ke Data Kelas
            </a>
        </div>
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Tingkatan</th>
                        <th class="px-6 py-4">Wali Kelas Saat Ini</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($kelas as $k)
                        @php
                            $assignment = $assignments[$k->id] ?? null;
                            $waliName = $assignment ? $assignment->user->name : '- Belum Ada -';
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 font-medium text-slate-800">
                                {{ $k->nama_kelas }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded text-xs font-semibold {{ $k->tingkatan == 'ula' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                    {{ strtoupper($k->tingkatan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($assignment)
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold">
                                            {{ substr($waliName, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-slate-700">{{ $waliName }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">{{ $waliName }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('kelas.edit_wali', $k->id) }}" class="inline-flex items-center px-3 py-2 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition text-sm font-medium border border-teal-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Atur Wali
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @foreach($kelas as $k)
                @php
                    $assignment = $assignments[$k->id] ?? null;
                    $waliName = $assignment ? $assignment->user->name : '- Belum Ada -';
                @endphp
                <div class="p-4 hover:bg-slate-50 transition">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">{{ $k->nama_kelas }}</h4>
                            <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-semibold {{ $k->tingkatan == 'ula' ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-100 text-indigo-700' }}">
                                {{ strtoupper($k->tingkatan) }}
                            </span>
                        </div>
                        <a href="{{ route('kelas.edit_wali', $k->id) }}" class="px-3 py-2 bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition text-sm font-medium border border-teal-200 shadow-sm">
                            Atur Wali
                        </a>
                    </div>
                    <div class="flex items-center gap-2 p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wide">Wali Kelas:</span>
                        @if($assignment)
                            <span class="font-medium text-slate-800">{{ $waliName }}</span>
                        @else
                            <span class="text-slate-400 italic text-sm">{{ $waliName }}</span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
