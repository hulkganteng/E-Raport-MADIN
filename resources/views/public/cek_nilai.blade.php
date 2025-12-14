<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cek Nilai - {{ config('app.name', 'Rapot Madin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-pattern {
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen bg-pattern flex items-center justify-center p-4">
    <div class="w-full max-w-lg">
        <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-10 border border-slate-100">
            <div class="text-center mb-6">
                <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 p-1 shadow-lg shadow-emerald-100">
                    <img src="{{ asset('logo.jpg') }}" alt="Logo Madin" class="w-full h-full rounded-full object-cover">
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800">Cek Nilai Rapot</h1>
                <p class="text-sm text-emerald-600 font-semibold tracking-wide">Masukkan NIS & Nama Lengkap</p>
                @if($periode)
                    <p class="text-xs text-slate-500 mt-1">Periode Aktif: {{ $periode->nama_periode }} ({{ ucfirst($periode->semester ?? 'ganjil') }})</p>
                @else
                    <p class="text-xs text-red-600 mt-1">Periode aktif belum ditetapkan.</p>
                @endif
            </div>

            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
                <p class="text-sm font-medium text-red-700">Data tidak ditemukan</p>
                @foreach($errors->all() as $error)
                    <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <form action="{{ route('public.cek_nilai.check') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="nis" class="block text-sm font-medium text-slate-700 mb-1.5">NIS</label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required placeholder="Contoh: 20240123" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Sesuai data santri" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:bg-white">
                </div>
                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold text-sm shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300">
                    Tampilkan Nilai
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-slate-600 hover:text-emerald-700 font-semibold">Kembali ke Login Admin/Guru</a>
            </div>
        </div>
    </div>
</body>
</html>
