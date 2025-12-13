@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Kenaikan Kelas & Kelulusan</h1>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Santri Aktif</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($kelasList as $kelas)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $kelas->nama_kelas }}</td>
                    <td class="px-6 py-4 whitespace-nowrap uppercase">{{ $kelas->tingkatan }} - {{ $kelas->tingkat }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $kelas->santri_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('kenaikan.show', $kelas->id) }}" class="text-indigo-600 hover:text-indigo-900">Proses</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
