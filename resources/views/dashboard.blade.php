@extends('layouts.app')

@section('header', 'Dashboard Overview')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <a href="{{ route('santri.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-teal-300 transition group">
        <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-teal-600">Total Santri Aktif</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalSantri }}</h3>
        </div>
    </a>
    
    <!-- Stat Card 2 -->
    <a href="{{ route('kelas.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-blue-300 transition group">
        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-blue-600">Total Kelas</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalKelas }}</h3>
        </div>
    </a>

    <!-- Stat Card 3 -->
    <a href="{{ route('mapel.index') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-purple-300 transition group">
        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-purple-600">Mata Pelajaran</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalMapel }}</h3>
        </div>
    </a>
    
    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 border-l-4 border-l-orange-400">
        <div class="w-12 h-12 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium">Periode Aktif</p>
            <h3 class="text-lg font-bold text-slate-800">{{ $periodeAktif->nama_periode ?? 'Belum Ada' }}</h3>
             @if($periodeAktif)
            <p class="text-xs text-orange-600 font-semibold">{{ \Carbon\Carbon::parse($periodeAktif->start_date)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($periodeAktif->end_date)->translatedFormat('d M Y') }}</p>
             @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Sistem</h3>
        <p class="text-slate-600 mb-4">Selamat datang di <strong>Sistem Rapot Digital Madrasah Diniyah</strong>. Gunakan menu di sidebar untuk mengelola data akademik.</p>
        
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
            <h4 class="font-bold text-sm text-slate-700 mb-2">Panduan Cepat:</h4>
            <ul class="list-disc list-inside text-sm text-slate-600 space-y-1">
                <li>Pastikan Data Master (Mapel, Kelas, Santri) sudah lengkap.</li>
                <li>Atur jadwal/mapel per kelas di menu <strong>Data Kelas</strong>.</li>
                <li>Input nilai harian dan ujian di menu <strong>Akademik &gt; Input Nilai</strong>.</li>
                <li>Cetak rapot lewat menu <strong>Data Kelas &gt; Rekap Nilai</strong>.</li>
            </ul>
        </div>
    </div>
    
    <!-- Simple Calendar / Date Widget -->
    <div class="bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl shadow-lg p-8 text-white flex flex-col justify-between">
        <div>
            <h2 class="text-3xl font-bold">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</h2>
            <p class="text-teal-100 text-lg">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="mt-8">
            <p class="text-sm text-teal-200 uppercase tracking-widest font-bold">Waktu Sekarang</p>
            <h1 class="text-5xl font-mono font-bold">{{ date('H:i') }}</h1>
        </div>
    </div>
</div>
@endsection
