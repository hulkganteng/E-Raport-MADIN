@extends('layouts.app')

@section('header', 'Input Nilai Anda')

@section('content')
<div class="space-y-6">
    @foreach($assignments as $kelasName => $mapels)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-4 bg-slate-50 border-b border-slate-200">
            <h3 class="font-bold text-slate-800">{{ $kelasName }}</h3>
        </div>
        <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($mapels as $km)
            <a href="{{ route('nilai.input', $km->id) }}" class="block p-4 border border-slate-200 rounded-xl hover:border-teal-500 hover:bg-teal-50 transition group">
                <h4 class="font-bold text-slate-700 group-hover:text-teal-700">{{ $km->mapel->nama_mapel }}</h4>
                <div class="flex justify-between items-center mt-2 text-xs text-slate-500">
                     <span class="px-2 py-0.5 bg-slate-200 rounded group-hover:bg-teal-200 group-hover:text-teal-800">KKM: {{ $km->kkm }}</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endforeach
    
    @if($assignments->isEmpty())
        <div class="text-center py-12 bg-white rounded-2xl border border-slate-200">
            <p class="text-slate-500">Anda belum memiliki jadwal mengajar.</p>
        </div>
    @endif
</div>
@endsection
