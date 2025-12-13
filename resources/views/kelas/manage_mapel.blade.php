@extends('layouts.app')

@section('header', 'Atur Pengajar & Mapel')

@section('content')
<div class="max-w-4xl mx-auto">
    
    @if(!$activePeriode)
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-red-700">
                    Tidak ada Periode (Tahun Ajaran) yang aktif. Silahkan aktifkan periode terlebih dahulu di menu "Master Data -> Tahun Ajar".
                </p>
            </div>
        </div>
    </div>
    @else
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-blue-700 font-medium">
                    Konfigurasi untuk Periode Aktif: {{ $activePeriode->nama_periode }} ({{ $activePeriode->semester }})
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Mapel & Guru Pengampu</h3>
                <p class="text-sm text-slate-500">Kelas: {{ $kelas->nama_kelas }} ({{ ucfirst($kelas->tingkatan) }})</p>
            </div>
            <a href="{{ route('kelas.index') }}" class="text-sm text-slate-500 hover:text-teal-600">Kembali</a>
        </div>
        
        <form action="{{ route('kelas.update_mapel', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                @if($available_mapels->isEmpty())
                    <p class="text-center text-slate-500 py-8">Belum ada mata pelajaran untuk tingkatan ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 w-10 text-center">
                                        <input type="checkbox" id="checkAll" class="rounded text-teal-600 focus:ring-teal-500">
                                    </th>
                                    <th class="px-4 py-3">Mata Pelajaran</th>
                                    <th class="px-4 py-3">Guru Pengampu</th>
                                    <th class="px-4 py-3 w-24">KKM</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($available_mapels as $mapel)
                                    @php
                                        // Check if this mapel is currently assigned in this period
                                        $record = $assignments->get($mapel->id);
                                        $isChecked = $record ? true : false;
                                        $currentGuruId = $record ? $record->guru_id : null;
                                        $currentKkm = $record ? $record->kkm : 65;
                                    @endphp
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-4 py-3 text-center">
                                            <input type="checkbox" name="mapel_ids[]" value="{{ $mapel->id }}" {{ $isChecked ? 'checked' : '' }} class="mapel-check rounded text-teal-600 focus:ring-teal-500">
                                        </td>
                                        <td class="px-4 py-3 font-medium text-slate-800">
                                            {{ $mapel->nama_mapel }}
                                            <span class="text-xs text-slate-400 block">{{ $mapel->kode_mapel }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <select name="meta[{{ $mapel->id }}][guru_id]" class="w-full text-sm border-slate-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                                                <option value="">-- Pilih Guru --</option>
                                                @foreach($gurus as $guru)
                                                    <option value="{{ $guru->id }}" {{ $currentGuruId == $guru->id ? 'selected' : '' }}>
                                                        {{ $guru->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-4 py-3">
                                            <input type="number" name="meta[{{ $mapel->id }}][kkm]" value="{{ $currentKkm }}" min="0" max="100" class="w-full text-sm border-slate-300 rounded-lg focus:ring-teal-500 focus:border-teal-500">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-200">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition font-bold shadow-lg shadow-teal-500/30" {{ !$activePeriode ? 'disabled' : '' }}>
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.mapel-check');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>
@endsection
