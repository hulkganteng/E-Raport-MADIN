<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nilai Santri - {{ $santri->nama_lengkap }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50">
    <div class="max-w-5xl mx-auto py-10 px-4">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <p class="text-xs uppercase text-slate-500">Periode</p>
                <h2 class="text-xl font-bold text-slate-800">{{ $periode->nama_periode }} ({{ ucfirst($periode->semester ?? 'ganjil') }})</h2>
                <p class="text-sm text-slate-500">Menampilkan nilai tanpa login berdasarkan NIS dan nama lengkap.</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-slate-500">Nama Santri</p>
                <h3 class="text-lg font-semibold text-slate-800">{{ $santri->nama_lengkap }}</h3>
                <p class="text-sm text-slate-600">NIS: {{ $santri->nis }}</p>
                <p class="text-sm text-slate-600">Kelas: {{ $santri->kelas->tingkatan }} ({{ $santri->kelas->nama_kelas }})</p>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-xs text-slate-500">Total Nilai</p>
                <h4 class="text-2xl font-bold text-emerald-600">{{ number_format($rekap->total_nilai ?? 0, 2) }}</h4>
                <p class="text-xs text-slate-500 mt-1">Rata-rata: {{ number_format($rekap->rata_rata ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-xs text-slate-500">Ranking</p>
                <h4 class="text-2xl font-bold text-indigo-600">{{ $rekap->ranking ?? '-' }}</h4>
                <p class="text-xs text-slate-500 mt-1">Dari {{ $totalSantri }} santri di kelas</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <p class="text-xs text-slate-500">Absensi (S/I/A)</p>
                <h4 class="text-xl font-semibold text-slate-800">{{ $absensi->sakit ?? 0 }} / {{ $absensi->izin ?? 0 }} / {{ $absensi->alpha ?? 0 }}</h4>
                <p class="text-xs text-slate-500 mt-1">Total: {{ ($absensi->sakit ?? 0) + ($absensi->izin ?? 0) + ($absensi->alpha ?? 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between">
                <h4 class="text-sm font-semibold text-slate-700">Daftar Nilai</h4>
                <span class="text-xs text-slate-500">Periode: {{ $periode->nama_periode }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-slate-700">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left">No</th>
                            <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                            <th class="px-4 py-3 text-center">Nilai</th>
                            <th class="px-4 py-3 text-center">Predikat</th>
                            <th class="px-4 py-3 text-center">Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($nilaiMapel as $idx => $nilai)
                        <tr>
                            <td class="px-4 py-3">{{ $idx + 1 }}</td>
                            <td class="px-4 py-3">{{ $nilai->kelasMapel->mapel->nama_mapel ?? '-' }}</td>
                            <td class="px-4 py-3 text-center font-semibold">{{ round($nilai->nilai_akhir) }}</td>
                            <td class="px-4 py-3 text-center">{{ $nilai->predikat }}</td>
                            <td class="px-4 py-3 text-center capitalize">{{ $nilai->kelasMapel->mapel->kategori ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-slate-500">Belum ada nilai pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-500">Catatan Wali Kelas</p>
                <p class="text-sm text-slate-700">{{ $rekap->catatan_wali ?? '-' }}</p>
            </div>
            <a href="{{ route('public.cek_nilai') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cek nilai santri lain
            </a>
        </div>
    </div>
</body>
</html>
