@extends('layouts.app')

@section('header', 'Rekap Nilai & Rapot: ' . $kelas->nama_kelas)

@section('content')
<div class="space-y-6">
    <!-- Action Bar -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 space-y-4">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3">
            <div>
                <h3 class="font-bold text-slate-800">Periode Aktif: {{ $periode->nama_periode }}</h3>
                <p class="text-xs text-slate-500">Pastikan semua nilai mapel sudah diinput sebelum hitung ranking.</p>
                <p class="text-xs text-slate-500 mt-1">Kelola cetak rapot dengan memilih tahun ajaran & jumlah salinan sebelum dicetak.</p>
            </div>
            <form action="{{ route('rekap.print_all', $kelas->id) }}" method="GET" target="_blank" class="flex flex-col md:flex-row items-end gap-2">
                <div class="flex flex-col text-xs text-slate-600">
                    <label class="font-semibold">Tahun Ajaran / Periode</label>
                    <select name="periode_id" class="mt-1 w-48 md:w-52 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($periodes as $periodeItem)
                            <option value="{{ $periodeItem->id }}" {{ $periodeItem->id === $periode->id ? 'selected' : '' }}>
                                {{ $periodeItem->nama_periode }} ({{ ucfirst($periodeItem->semester) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col text-xs text-slate-600">
                    <label class="font-semibold">Jumlah Salinan</label>
                    <input type="number" name="copies" value="1" min="1" max="10" class="mt-1 w-32 border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-lg hover:bg-slate-900 transition text-sm font-bold shadow-md flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Semua Rapot
                </button>
            </form>
        </div>
        <div class="flex justify-end">
            <form action="{{ route('rekap.ranking', $kelas->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-bold shadow-md shadow-indigo-500/30">
                    Hitung Ranking Otomatis
                </button>
            </form>
        </div>
    </div>

    <!-- Student List -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 w-10">Rank</th>
                        <th class="px-6 py-4">Nama Santri</th>
                        <th class="px-6 py-4 text-center">Total Nilai</th>
                        <th class="px-6 py-4 text-center">Rata-rata</th>
                        <th class="px-6 py-4 text-center">Absensi (S/I/A)</th>
                        <th class="px-6 py-4 text-center">Aksi Rapot</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rekaps as $rekap)
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-6 py-4 text-center font-bold text-lg text-teal-700">
                            {{ $rekap->ranking ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $rekap->santri->nama_lengkap }}
                            <div class="mt-2 hidden group-hover:block">
                                <!-- Inline Edit Form for Absensi & Personality (Simple Trigger) -->
                                <button onclick="document.getElementById('modal-{{ $rekap->id }}').showModal()" class="text-xs text-teal-600 hover:text-teal-800 underline">
                                    Edit Absensi & Catatan
                                </button>
                            </div>
                            
                            <!-- Modal for Editing Data Wali -->
                            <dialog id="modal-{{ $rekap->id }}" class="p-0 rounded-xl shadow-2xl backdrop:bg-gray-900/50 w-full max-w-lg">
                                <form action="{{ route('rekap.update', $rekap->id) }}" method="POST" class="bg-white p-6">
                                    @csrf
                                    @method('PUT')
                                    <h3 class="font-bold text-lg mb-4">Data Wali: {{ $rekap->santri->nama_lengkap }}</h3>
                                    
                                    <div class="grid grid-cols-3 gap-2 mb-4">
                                        @php
                                            $absensi = \App\Models\Absensi::where('santri_id', $rekap->santri_id)->where('periode_id', $rekap->periode_id)->first();
                                        @endphp
                                        <div>
                                            <label class="text-xs">Sakit</label>
                                            <input type="number" name="sakit" value="{{ $absensi->sakit ?? 0 }}" class="w-full border rounded p-1">
                                        </div>
                                        <div>
                                            <label class="text-xs">Izin</label>
                                            <input type="number" name="izin" value="{{ $absensi->izin ?? 0 }}" class="w-full border rounded p-1">
                                        </div>
                                        <div>
                                            <label class="text-xs">Alpha</label>
                                            <input type="number" name="alpha" value="{{ $absensi->alpha ?? 0 }}" class="w-full border rounded p-1">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div><label class="text-xs">Akhlaq</label><input type="text" name="akhlaq" value="{{ $rekap->akhlaq }}" class="w-full border rounded p-1"></div>
                                        <div><label class="text-xs">Kerajinan</label><input type="text" name="kerajinan" value="{{ $rekap->kerajinan }}" class="w-full border rounded p-1"></div>
                                        <div><label class="text-xs">Kedisiplinan</label><input type="text" name="kedisiplinan" value="{{ $rekap->kedisiplinan }}" class="w-full border rounded p-1"></div>
                                        <div><label class="text-xs">Kerapihan</label><input type="text" name="kerapihan" value="{{ $rekap->kerapihan }}" class="w-full border rounded p-1"></div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="text-xs">Catatan Wali Kelas</label>
                                        <textarea name="catatan_wali" rows="2" class="w-full border rounded p-1">{{ $rekap->catatan_wali }}</textarea>
                                    </div>

                                    <div class="flex justify-end gap-2">
                                        <button type="button" onclick="document.getElementById('modal-{{ $rekap->id }}').close()" class="px-3 py-1 bg-slate-200 rounded">Batal</button>
                                        <button type="submit" class="px-3 py-1 bg-teal-600 text-white rounded">Simpan</button>
                                    </div>
                                </form>
                            </dialog>
                        </td>
                        <td class="px-6 py-4 text-center">{{ number_format($rekap->total_nilai, 2) }}</td>
                        <td class="px-6 py-4 text-center">{{ number_format($rekap->rata_rata, 2) }}</td>
                        <td class="px-6 py-4 text-center text-xs">
                             @php $abs = \App\Models\Absensi::where('santri_id', $rekap->santri_id)->where('periode_id', $rekap->periode_id)->first(); @endphp
                             {{ $abs->sakit ?? 0 }} / {{ $abs->izin ?? 0 }} / {{ $abs->alpha ?? 0 }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('rekap.print', $rekap->santri_id) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-800 text-white text-xs rounded-lg hover:bg-slate-700 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak Cepat
                                </a>
                                <button type="button" data-url="{{ route('rekap.print', $rekap->santri_id) }}" data-name="Rapot {{ $rekap->santri->nama_lengkap }}" data-periode-id="{{ $periode->id }}" onclick="openPrintModal(this)" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 text-xs rounded-lg hover:bg-slate-50 transition shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                    Atur Cetak
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal: Kelola Cetak Per Santri -->
    <dialog id="print-modal" class="p-0 rounded-xl shadow-2xl backdrop:bg-gray-900/50 w-full max-w-xl">
        <form method="GET" id="print-modal-form" target="_blank" class="bg-white p-6 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Atur Cetak Rapot</h3>
                    <p class="text-sm text-slate-600" id="print-modal-student">Pilih santri dari tabel untuk mencetak.</p>
                </div>
                <button type="button" onclick="closePrintModal()" class="text-slate-500 hover:text-slate-700">✕</button>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-semibold text-slate-600">Tahun Ajaran / Periode</label>
                    <select id="print-modal-periode" name="periode_id" class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach($periodes as $periodeItem)
                            <option value="{{ $periodeItem->id }}" {{ $periodeItem->id === $periode->id ? 'selected' : '' }}>
                                {{ $periodeItem->nama_periode }} ({{ ucfirst($periodeItem->semester) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-slate-600">Jumlah Salinan</label>
                    <input id="print-modal-copies" type="number" name="copies" value="1" min="1" max="10" class="mt-1 w-full border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePrintModal()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition text-sm">Batal</button>
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-bold shadow-md shadow-teal-500/30">Cetak Rapot</button>
            </div>
        </form>
    </dialog>

    <script>
        const printModal = document.getElementById('print-modal');
        const printModalForm = document.getElementById('print-modal-form');
        const printModalStudent = document.getElementById('print-modal-student');
        const printModalPeriode = document.getElementById('print-modal-periode');
        const printModalCopies = document.getElementById('print-modal-copies');

        function openPrintModal(button) {
            printModalForm.action = button.dataset.url;
            printModalStudent.textContent = button.dataset.name || 'Cetak rapot santri terpilih.';
            if (button.dataset.periodeId) {
                printModalPeriode.value = button.dataset.periodeId;
            }
            printModalCopies.value = 1;
            printModal.showModal();
        }

        function closePrintModal() {
            printModal.close();
        }
    </script>
</div>
@endsection
