@extends('layouts.app')

@section('header', 'Rekap Nilai & Rapot: ' . $kelas->nama_kelas)

@push('head')
<style>
    .rekap-dialog {
        width: min(1080px, calc(100vw - 32px));
        max-height: min(92vh, 860px);
        margin: auto;
        border: 0;
        padding: 0;
        overflow: hidden;
        border-radius: 14px;
        box-shadow: 0 24px 80px rgba(15, 23, 42, 0.28);
    }

    .rekap-dialog::backdrop {
        background: rgba(15, 23, 42, 0.62);
        backdrop-filter: blur(2px);
    }

    .rekap-dialog-shell {
        display: flex;
        max-height: min(92vh, 860px);
        flex-direction: column;
    }

    .rekap-dialog-body {
        overflow-y: auto;
    }

    .nilai-grid-row {
        display: grid;
        grid-template-columns: minmax(190px, 1.4fr) minmax(96px, 0.6fr) minmax(96px, 0.6fr) minmax(80px, 0.45fr) minmax(72px, 0.35fr);
        gap: 12px;
        align-items: center;
    }

    @media (max-width: 760px) {
        .rekap-dialog {
            width: calc(100vw - 20px);
            max-height: 94vh;
        }

        .rekap-dialog-shell {
            max-height: 94vh;
        }

        .nilai-grid-row {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .nilai-grid-row .nilai-mapel-name {
            grid-column: 1 / -1;
        }
    }
</style>
@endpush

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
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($rekaps as $rekap)
                    @php
                        $studentGrades = $nilaiBySantri->get($rekap->santri_id, collect());
                        $studentAbsensi = $absensiBySantri->get($rekap->santri_id);
                    @endphp
                    <tr class="hover:bg-slate-50 transition group">
                        <td class="px-6 py-4 text-center font-bold text-lg text-teal-700">
                            {{ $rekap->ranking ?? '-' }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $rekap->santri->nama_lengkap }}
                            <span class="block text-xs text-slate-400 mt-1">NIS: {{ $rekap->santri->nis }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">{{ number_format($rekap->total_nilai, 2) }}</td>
                        <td class="px-6 py-4 text-center">{{ number_format($rekap->rata_rata, 2) }}</td>
                        <td class="px-6 py-4 text-center text-xs">
                             {{ $studentAbsensi->sakit ?? 0 }} / {{ $studentAbsensi->izin ?? 0 }} / {{ $studentAbsensi->alpha ?? 0 }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2 whitespace-nowrap">
                                <button type="button" onclick="openRekapModal('modal-{{ $rekap->id }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-teal-600 text-white text-xs rounded-lg hover:bg-teal-700 transition shadow-sm">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Edit Nilai
                                </button>
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

    @foreach($rekaps as $rekap)
        @php
            $studentGrades = $nilaiBySantri->get($rekap->santri_id, collect());
            $studentAbsensi = $absensiBySantri->get($rekap->santri_id);
        @endphp
        <dialog id="modal-{{ $rekap->id }}" class="rekap-dialog">
            <form action="{{ route('rekap.update', $rekap->id) }}" method="POST" class="rekap-dialog-shell bg-white">
                @csrf
                @method('PUT')
                <input type="hidden" name="rekap_id" value="{{ $rekap->id }}">

                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 sm:px-6 py-4 bg-white">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold text-slate-900">Edit Nilai & Rapot</h3>
                        <p class="text-sm text-slate-500 mt-1">{{ $rekap->santri->nama_lengkap }} - {{ $kelas->nama_kelas }} - {{ $periode->nama_periode }}</p>
                    </div>
                    <button type="button" onclick="closeRekapModal('modal-{{ $rekap->id }}')" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="rekap-dialog-body px-5 sm:px-6 py-5 space-y-6">
                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <section>
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <h4 class="font-bold text-slate-800">Nilai Mata Pelajaran</h4>
                            <span class="text-xs text-slate-500">Nilai akhir dihitung otomatis dari bobot mapel.</span>
                        </div>

                        @if($kelasMapels->isEmpty())
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 text-sm text-slate-500">
                                Belum ada mapel yang diatur untuk kelas dan periode ini.
                            </div>
                        @else
                            <div class="space-y-3">
                                <div class="hidden md:grid nilai-grid-row rounded-lg bg-slate-50 px-4 py-3 text-xs font-bold uppercase text-slate-600">
                                    <div>Mapel</div>
                                    <div class="text-center">Harian</div>
                                    <div class="text-center">Ujian</div>
                                    <div class="text-center">Akhir</div>
                                    <div class="text-center">Predikat</div>
                                </div>
                                @foreach($kelasMapels as $kelasMapel)
                                    @php
                                        $grade = $studentGrades->get($kelasMapel->id);
                                        $canEditNilai = $editableKelasMapelIds->contains($kelasMapel->id);
                                    @endphp
                                    <div class="nilai-grid-row rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
                                        <div class="nilai-mapel-name min-w-0">
                                            <span class="block truncate font-semibold text-slate-800">{{ $kelasMapel->mapel->nama_mapel }}</span>
                                            <span class="block text-xs text-slate-500">Bobot {{ $kelasMapel->mapel->bobot_harian }}% / {{ $kelasMapel->mapel->bobot_ujian }}%</span>
                                            @if(!$canEditNilai)
                                                <span class="mt-1 inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-500">Hanya lihat</span>
                                            @endif
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-500 md:hidden">Harian</label>
                                            <input type="number" step="0.01" min="0" max="100" name="nilai[{{ $kelasMapel->id }}][harian]" value="{{ old('nilai.' . $kelasMapel->id . '.harian', $grade->nilai_harian ?? '') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-center text-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-slate-100 disabled:text-slate-500" placeholder="0" {{ $canEditNilai ? '' : 'disabled' }}>
                                        </div>
                                        <div>
                                            <label class="mb-1 block text-xs font-semibold text-slate-500 md:hidden">Ujian</label>
                                            <input type="number" step="0.01" min="0" max="100" name="nilai[{{ $kelasMapel->id }}][ujian]" value="{{ old('nilai.' . $kelasMapel->id . '.ujian', $grade->nilai_ujian ?? '') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-center text-sm focus:border-teal-500 focus:ring-teal-500 disabled:bg-slate-100 disabled:text-slate-500" placeholder="0" {{ $canEditNilai ? '' : 'disabled' }}>
                                        </div>
                                        <div>
                                            <span class="mb-1 block text-xs font-semibold text-slate-500 md:hidden">Akhir</span>
                                            <div class="rounded-lg bg-slate-50 px-3 py-2 text-center text-sm font-semibold text-slate-700">
                                                {{ $grade && $grade->nilai_akhir !== null ? number_format($grade->nilai_akhir, 2) : '-' }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="mb-1 block text-xs font-semibold text-slate-500 md:hidden">Predikat</span>
                                            <div class="rounded-lg bg-slate-50 px-3 py-2 text-center text-sm font-semibold text-slate-700">
                                                {{ $grade->predikat ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </section>

                    <section class="grid gap-5 lg:grid-cols-[260px,1fr]">
                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">Absensi</h4>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Sakit</label>
                                    <input type="number" name="sakit" value="{{ old('sakit', $studentAbsensi->sakit ?? 0) }}" min="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-sm focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Izin</label>
                                    <input type="number" name="izin" value="{{ old('izin', $studentAbsensi->izin ?? 0) }}" min="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-sm focus:border-teal-500 focus:ring-teal-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Alpha</label>
                                    <input type="number" name="alpha" value="{{ old('alpha', $studentAbsensi->alpha ?? 0) }}" min="0" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-center text-sm focus:border-teal-500 focus:ring-teal-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">Kepribadian & Catatan</h4>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Akhlaq</label>
                                    <select name="akhlaq" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $gradeOption)
                                            <option value="{{ $gradeOption }}" {{ old('akhlaq', $rekap->akhlaq) === $gradeOption ? 'selected' : '' }}>{{ $gradeOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kerajinan</label>
                                    <select name="kerajinan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $gradeOption)
                                            <option value="{{ $gradeOption }}" {{ old('kerajinan', $rekap->kerajinan) === $gradeOption ? 'selected' : '' }}>{{ $gradeOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kedisiplinan</label>
                                    <select name="kedisiplinan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $gradeOption)
                                            <option value="{{ $gradeOption }}" {{ old('kedisiplinan', $rekap->kedisiplinan) === $gradeOption ? 'selected' : '' }}>{{ $gradeOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Kerapihan</label>
                                    <select name="kerapihan" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'C', 'D'] as $gradeOption)
                                            <option value="{{ $gradeOption }}" {{ old('kerapihan', $rekap->kerapihan) === $gradeOption ? 'selected' : '' }}>{{ $gradeOption }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Wali Kelas</label>
                                <textarea name="catatan_wali" rows="3" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('catatan_wali', $rekap->catatan_wali) }}</textarea>
                            </div>
                        </div>
                    </section>
                </div>

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 border-t border-slate-200 bg-slate-50 px-5 sm:px-6 py-4">
                    <button type="button" onclick="closeRekapModal('modal-{{ $rekap->id }}')" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-100 transition text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-bold shadow-md shadow-teal-500/30">Simpan Perubahan</button>
                </div>
            </form>
        </dialog>
    @endforeach

    <!-- Modal: Kelola Cetak Per Santri -->
    <dialog id="print-modal" class="p-0 rounded-xl shadow-2xl backdrop:bg-gray-900/50 w-full max-w-xl">
        <form method="GET" id="print-modal-form" target="_blank" class="bg-white p-6 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Atur Cetak Rapot</h3>
                    <p class="text-sm text-slate-600" id="print-modal-student">Pilih santri dari tabel untuk mencetak.</p>
                </div>
                <button type="button" onclick="closePrintModal()" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
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

        function openRekapModal(id) {
            document.getElementById(id).showModal();
        }

        function closeRekapModal(id) {
            document.getElementById(id).close();
        }

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

        document.querySelectorAll('dialog').forEach((dialog) => {
            dialog.addEventListener('click', function (event) {
                if (event.target === dialog) {
                    dialog.close();
                }
            });
        });

        const failedModalId = @json(old('rekap_id') ? 'modal-' . old('rekap_id') : null);
        if (failedModalId && document.getElementById(failedModalId)) {
            openRekapModal(failedModalId);
        }
    </script>
</div>
@endsection
