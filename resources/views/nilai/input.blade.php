@extends('layouts.app')

@push('head')
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
@endpush

@section('header', 'Input Nilai: ' . $kelasMapel->mapel->nama_mapel)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h2 class="text-xl font-bold text-slate-800">{{ $kelasMapel->kelas->nama_kelas }}</h2>
        <p class="text-slate-500 text-sm">Periode: {{ $periode->nama_periode }} | KKM: <span class="font-bold text-teal-600">{{ $kelasMapel->kkm }}</span></p>
    </div>
    <a href="{{ route('nilai.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition text-sm font-semibold whitespace-nowrap">Kembali</a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <form action="{{ route('nilai.store', $kelasMapel->id) }}" method="POST">
        @csrf
        
        @if($errors->any())
            <div class="p-4 bg-red-50 border-b border-red-200 text-red-700 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 w-10">No</th>
                        <th class="px-6 py-4">Nama Santri</th>
                        <th class="px-6 py-4 w-32 text-center">Nilai Harian <br><span class="text-[10px] text-slate-500">Bobot: {{ $kelasMapel->mapel->bobot_harian }}%</span></th>
                        <th class="px-6 py-4 w-32 text-center">Nilai Ujian <br><span class="text-[10px] text-slate-500">Bobot: {{ $kelasMapel->mapel->bobot_ujian }}%</span></th>
                        <th class="px-6 py-4 w-24 text-center">Nilai Akhir</th>
                        <th class="px-6 py-4 w-16 text-center">Predikat</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($santris as $index => $santri)
                    @php
                        $grade = $existingGrades[$santri->id] ?? null;
                    @endphp
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-center">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $santri->nama_lengkap }}</td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" min="0" max="100" 
                                name="nilai[{{ $santri->id }}][harian]" 
                                value="{{ old('nilai.' . $santri->id . '.harian', $grade ? $grade->nilai_harian : '') }}" 
                                class="w-full text-center rounded-lg border-slate-300 focus:border-teal-500 focus:ring-teal-500 nilai-input" 
                                data-view="desktop"
                                data-santri-id="{{ $santri->id }}" 
                                data-type="harian"
                                placeholder="0">
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" step="0.01" min="0" max="100" 
                                name="nilai[{{ $santri->id }}][ujian]" 
                                value="{{ old('nilai.' . $santri->id . '.ujian', $grade ? $grade->nilai_ujian : '') }}" 
                                class="w-full text-center rounded-lg border-slate-300 focus:border-teal-500 focus:ring-teal-500 nilai-input" 
                                data-view="desktop"
                                data-santri-id="{{ $santri->id }}" 
                                data-type="ujian"
                                placeholder="0">
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-slate-700">
                            {{ $grade && $grade->nilai_akhir !== null ? number_format($grade->nilai_akhir, 2) : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($grade && $grade->predikat)
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $grade->predikat == 'A' ? 'bg-green-100 text-green-700' : ($grade->predikat == 'D' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                    {{ $grade->predikat }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @foreach($santris as $index => $santri)
            @php
                $grade = $existingGrades[$santri->id] ?? null;
            @endphp
            <div class="p-4">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-800">{{ $index + 1 }}. {{ $santri->nama_lengkap }}</h4>
                    </div>
                    @if($grade)
                    <span class="px-2 py-1 rounded text-xs font-bold {{ $grade->predikat == 'A' ? 'bg-green-100 text-green-700' : ($grade->predikat == 'D' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                        {{ $grade->predikat }}
                    </span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Nilai Harian ({{ $kelasMapel->mapel->bobot_harian }}%)
                        </label>
                            <input type="number" step="0.01" min="0" max="100" 
                                name="nilai[{{ $santri->id }}][harian]" 
                                value="{{ old('nilai.' . $santri->id . '.harian', $grade ? $grade->nilai_harian : '') }}" 
                                class="w-full text-center text-lg rounded-lg border-slate-300 focus:border-teal-500 focus:ring-teal-500 nilai-input" 
                                data-view="mobile"
                                data-santri-id="{{ $santri->id }}" 
                                data-type="harian"
                                placeholder="0">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 mb-1">
                            Nilai Ujian ({{ $kelasMapel->mapel->bobot_ujian }}%)
                        </label>
                        <input type="number" step="0.01" min="0" max="100" 
                            name="nilai[{{ $santri->id }}][ujian]" 
                            value="{{ old('nilai.' . $santri->id . '.ujian', $grade ? $grade->nilai_ujian : '') }}" 
                            class="w-full text-center text-lg rounded-lg border-slate-300 focus:border-teal-500 focus:ring-teal-500 nilai-input" 
                            data-view="mobile"
                            data-santri-id="{{ $santri->id }}" 
                            data-type="ujian"
                            placeholder="0">
                    </div>
                </div>
                
                @if($grade && $grade->nilai_akhir !== null)
                    <div class="flex justify-between items-center text-sm bg-slate-50 rounded-lg p-3">
                        <span class="text-slate-600 font-medium">Nilai Akhir:</span>
                        <span class="text-lg font-bold text-teal-700">{{ number_format($grade->nilai_akhir, 2) }}</span>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="p-4 sm:p-6 bg-slate-50 border-t border-slate-200 flex justify-end">
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-bold shadow-lg shadow-teal-500/30">
                Simpan Nilai
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    
    // 1. Sinkronisasi input antara tampilan Desktop dan Mobile
    // Menggunakan data attributes untuk mencocokkan input yang sama
    const allInputs = document.querySelectorAll('.nilai-input');

    function syncDisabledState() {
        const activeView = window.matchMedia('(min-width: 768px)').matches ? 'desktop' : 'mobile';
        allInputs.forEach(input => {
            input.disabled = input.dataset.view !== activeView;
        });
    }

    syncDisabledState();
    window.addEventListener('resize', syncDisabledState);
    
    allInputs.forEach(input => {
        input.addEventListener('input', function() {
            const santriId = this.getAttribute('data-santri-id');
            const type = this.getAttribute('data-type');
            const value = this.value;
            
            // Cari input pasangannya (desktop/mobile view)
            const matchingInputs = document.querySelectorAll(`.nilai-input[data-santri-id="${santriId}"][data-type="${type}"]`);
            
            matchingInputs.forEach(matchInput => {
                if (matchInput !== this) {
                    matchInput.value = value;
                }
            });
        });
    });
    
    // 2. Tambahkan loading state pada tombol submit
    form.addEventListener('submit', function(e) {
        syncDisabledState();
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';
    });
});
</script>
@endsection
