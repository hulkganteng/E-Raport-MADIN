@extends('layouts.app')

@section('header', 'Proses Kenaikan: ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <a href="{{ route('kenaikan.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-teal-600 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar Kelas
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $kelas->nama_kelas }}</h3>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-bold {{ $kelas->tingkatan == 'ula' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($kelas->tingkatan) }} - {{ $kelas->tingkat }}
                    </span>
                </div>
            </div>
        </div>

        <form action="{{ route('kenaikan.process', $kelas->id) }}" method="POST">
            @csrf

            <div class="p-4 sm:p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Aksi</label>
                        <select name="action" id="action" required onchange="toggleTargetClass()" class="block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            @if($canContinueToWustho)
                                <option value="graduate_continue">Lulus Ula & Lanjut Wustho</option>
                                <option value="graduate">Lulus Ula / Tidak Lanjut</option>
                            @elseif($isWusthoFinal)
                                <option value="graduate">Luluskan Santri</option>
                            @else
                                <option value="promote">Naik Kelas</option>
                            @endif
                            <option value="retain">Tinggal Kelas</option>
                        </select>
                    </div>

                    <div id="target-kelas-container">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ $canContinueToWustho ? 'Target Kelas Wustho' : 'Target Kelas Baru' }}
                        </label>
                        <select name="target_kelas_id" id="target_kelas_id" class="block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                            <option value="">-- Pilih Kelas Tujuan --</option>
                            @foreach($targetKelasList as $target)
                                <option value="{{ $target->id }}">{{ $target->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="font-bold text-slate-800">Daftar Santri</h4>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-600 cursor-pointer">
                            <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" checked class="rounded text-teal-600 focus:ring-teal-500">
                            <span>Pilih Semua</span>
                        </label>
                    </div>

                    <!-- Desktop Table View -->
                    <div class="hidden md:block overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 w-16 text-center">Pilih</th>
                                    <th class="px-4 py-3">NIS</th>
                                    <th class="px-4 py-3">Nama Lengkap</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($santriList as $santri)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-4 py-3 text-center">
                                        <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" checked class="santri-checkbox rounded text-teal-600 focus:ring-teal-500">
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs">{{ $santri->nis }}</td>
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $santri->nama_lengkap }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-slate-400">Tidak ada santri aktif di kelas ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card View -->
                    <div class="md:hidden rounded-xl border border-slate-200 divide-y divide-slate-100">
                        @forelse($santriList as $santri)
                        <label class="flex items-start gap-3 p-3 hover:bg-slate-50 transition cursor-pointer">
                            <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" checked class="santri-checkbox mt-1 rounded text-teal-600 focus:ring-teal-500">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-slate-800 truncate">{{ $santri->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500 font-mono mt-0.5">NIS: {{ $santri->nis }}</p>
                            </div>
                        </label>
                        @empty
                        <div class="p-6 text-center text-slate-400 text-sm">Tidak ada santri aktif di kelas ini.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-slate-50 px-4 sm:px-6 py-4 border-t border-slate-200 flex justify-end">
                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memproses data ini?')" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">
                    Proses Kenaikan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleTargetClass() {
        const action = document.getElementById('action').value;
        const targetContainer = document.getElementById('target-kelas-container');
        const targetSelect = document.getElementById('target_kelas_id');

        if (action === 'promote' || action === 'graduate_continue') {
            targetContainer.style.display = '';
            targetSelect.required = true;
        } else {
            targetContainer.style.display = 'none';
            targetSelect.required = false;
        }
    }

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.santri-checkbox');
        checkboxes.forEach(function (cb) { cb.checked = source.checked; });
    }

    document.addEventListener('DOMContentLoaded', toggleTargetClass);
</script>
@endsection
