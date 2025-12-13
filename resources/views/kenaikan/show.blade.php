@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold">Proses Kenaikan/Kelulusan - Kelas {{ $kelas->nama_kelas }}</h1>
        <a href="{{ route('kenaikan.index') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali</a>
    </div>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('kenaikan.process', $kelas->id) }}" method="POST">
            @csrf
            
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Aksi</label>
                    <select name="action" id="action" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required onchange="toggleTargetClass()">
                        <option value="promote">Naik Kelas</option>
                        <option value="retain">Tinggal Kelas</option>
                        @if($canGraduate)
                        <option value="graduate" class="font-bold text-green-600">Luluskan Santri</option>
                        @endif
                    </select>
                </div>
                
                <div id="target-kelas-container">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Target Kelas Baru</label>
                    <select name="target_kelas_id" id="target_kelas_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">-- Pilih Kelas Tujuan --</option>
                        @foreach($targetKelasList as $target)
                        <option value="{{ $target->id }}">{{ $target->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-lg font-semibold">Daftar Santri</h3>
                    <div>
                        <input type="checkbox" id="select-all" onclick="toggleSelectAll(this)" checked class="mr-2">
                        <label for="select-all">Pilih Semua</label>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left w-10">Pilih</th>
                                <th class="px-4 py-2 text-left">NIS</th>
                                <th class="px-4 py-2 text-left">Nama Lengkap</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($santriList as $santri)
                            <tr>
                                <td class="px-4 py-2">
                                    <input type="checkbox" name="santri_ids[]" value="{{ $santri->id }}" checked class="santri-checkbox">
                                </td>
                                <td class="px-4 py-2">{{ $santri->nis }}</td>
                                <td class="px-4 py-2">{{ $santri->nama_lengkap }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-center text-gray-500">Tidak ada santri aktif di kelas ini.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Apakah Anda yakin ingin memproses data ini?')">
                    Proses
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
        
        if (action === 'promote') {
            targetContainer.style.display = 'block';
            targetSelect.required = true;
        } else {
            targetContainer.style.display = 'none';
            targetSelect.required = false;
        }
    }

    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.santri-checkbox');
        for(let i=0; i<checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    // Initial check
    toggleTargetClass();
</script>
@endsection
