@extends('layouts.app')

@section('header', 'Beranda')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <a href="{{ route('santri.index') }}" class="min-h-[128px] bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-teal-300 transition group">
        <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center group-hover:bg-teal-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-teal-600">Total Santri Aktif</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalSantri }}</h3>
        </div>
    </a>
    
    <!-- Stat Card 2 -->
    <a href="{{ route('kelas.index') }}" class="min-h-[128px] bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-blue-300 transition group">
        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-blue-600">Total Kelas</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalKelas }}</h3>
        </div>
    </a>

    <!-- Stat Card 3 -->
    <a href="{{ route('mapel.index') }}" class="min-h-[128px] bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 hover:border-purple-300 transition group">
        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
        </div>
        <div>
            <p class="text-sm text-slate-500 font-medium group-hover:text-purple-600">Mata Pelajaran</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ $totalMapel }}</h3>
        </div>
    </a>
    
    <!-- Stat Card 4 -->
    <div class="min-h-[128px] bg-white rounded-2xl p-6 shadow-sm border border-slate-100 flex items-center gap-4 border-l-4 border-l-orange-400">
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

@if(auth()->user()->role === 'super_admin')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6 items-start">
    <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-full">
        <div class="flex items-start justify-between gap-3 mb-5">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Checklist Kelengkapan Data</h3>
                <p class="text-sm text-slate-500 mt-1">Ikuti urutan ini saat menyiapkan tahun ajar baru.</p>
            </div>
            @php
                $completedSteps = collect($setupChecklist)->where('complete', true)->count();
                $totalSteps = count($setupChecklist);
            @endphp
            <span class="shrink-0 px-3 py-1 rounded-full bg-teal-50 text-teal-700 text-xs font-bold border border-teal-100">
                {{ $completedSteps }}/{{ $totalSteps }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            @foreach($setupChecklist as $item)
                <a href="{{ route($item['route']) }}" class="block h-full rounded-xl border {{ $item['complete'] ? 'border-emerald-100 bg-emerald-50/60' : 'border-amber-100 bg-amber-50/60' }} p-4 hover:border-teal-300 transition">
                    <div class="flex gap-3">
                        <span class="mt-0.5 flex h-6 w-6 items-center justify-center rounded-full {{ $item['complete'] ? 'bg-emerald-600 text-white' : 'bg-amber-500 text-white' }} text-xs font-bold">
                            {!! $item['complete'] ? '&#10003;' : '!' !!}
                        </span>
                        <div>
                            <p class="text-sm font-bold text-slate-800">{{ $item['label'] }}</p>
                            <p class="text-xs text-slate-600 mt-1">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Sistem</h3>
            <p class="text-slate-600 mb-4">Selamat datang di <strong>Sistem Rapot Digital {{ lembaga_setting('jenjang', 'Lembaga') }}</strong>. Gunakan menu di sidebar untuk mengelola data akademik.</p>
            
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <h4 class="font-bold text-sm text-slate-700 mb-2">Panduan Cepat:</h4>
                <ul class="list-disc list-inside text-sm text-slate-600 space-y-1">
                    <li>Pastikan Data Master sudah lengkap.</li>
                    <li>Atur mapel per kelas di menu <strong>Data Kelas</strong>.</li>
                    <li>Input nilai di menu <strong>Akademik &gt; Input Nilai</strong>.</li>
                    <li>Cetak rapot lewat <strong>Data Kelas &gt; Rekap Nilai</strong>.</li>
                </ul>
            </div>
        </div>
        
        <!-- Simple Calendar / Date Widget -->
        <div class="min-h-[220px] bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl shadow-lg p-8 text-white flex flex-col justify-between">
            <div>
                <h2 class="text-3xl font-bold" id="clock-day-1">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</h2>
                <p class="text-teal-100 text-lg" id="clock-date-1">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            </div>
            <div class="mt-8">
                <p class="text-sm text-teal-200 uppercase tracking-widest font-bold">Waktu Sekarang</p>
                <h1 class="text-5xl font-mono font-bold tabular-nums" id="clock-time-1">{{ date('H:i:s') }}</h1>
            </div>
        </div>
    </div>
</div>
@else
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8 h-full">
        <h3 class="text-lg font-bold text-slate-800 mb-4">Informasi Sistem</h3>
        <p class="text-slate-600 mb-4">Selamat datang di <strong>Sistem Rapot Digital {{ lembaga_setting('jenjang', 'Lembaga') }}</strong>. Gunakan menu di sidebar untuk mengelola data akademik.</p>
        
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
    <div class="min-h-[260px] bg-gradient-to-br from-teal-500 to-teal-700 rounded-2xl shadow-lg p-8 text-white flex flex-col justify-between h-full">
        <div>
            <h2 class="text-3xl font-bold" id="clock-day-2">{{ \Carbon\Carbon::now()->translatedFormat('l') }}</h2>
            <p class="text-teal-100 text-lg" id="clock-date-2">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="mt-8">
            <p class="text-sm text-teal-200 uppercase tracking-widest font-bold">Waktu Sekarang</p>
            <h1 class="text-5xl font-mono font-bold tabular-nums" id="clock-time-2">{{ date('H:i:s') }}</h1>
        </div>
    </div>
</div>
@endif

<script>
(function() {
    const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    function pad(n) { return n < 10 ? '0' + n : n; }

    function updateClocks() {
        const now = new Date();
        const day = hari[now.getDay()];
        const date = now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
        const time = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());

        ['1', '2'].forEach(function(id) {
            var dayEl = document.getElementById('clock-day-' + id);
            var dateEl = document.getElementById('clock-date-' + id);
            var timeEl = document.getElementById('clock-time-' + id);
            if (dayEl) dayEl.textContent = day;
            if (dateEl) dateEl.textContent = date;
            if (timeEl) timeEl.textContent = time;
        });
    }

    updateClocks();
    setInterval(updateClocks, 1000);
})();
</script>
@endsection
