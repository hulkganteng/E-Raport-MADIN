@extends('layouts.app')

@section('header', 'Manajemen User (Guru & Staff)')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-4 sm:p-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-4">
        <h3 class="text-lg font-bold text-slate-800">Daftar Pengguna</h3>
        <a href="{{ route('users.create') }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition text-sm font-semibold shadow-md shadow-teal-500/30 flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah User
        </a>
    </div>
    
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-800 font-semibold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Role</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($users as $user)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-6 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                    <td class="px-6 py-4">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex px-2 py-1 text-xs font-bold leading-5 rounded-md 
                            {{ $user->role == 'super_admin' ? 'bg-purple-100 text-purple-700' : '' }}
                            {{ $user->role == 'admin' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $user->role == 'guru' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $user->role == 'wali_kelas' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $user->role == 'kepsek' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('users.edit', $user) }}" class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-slate-100">
        @foreach($users as $user)
        <div class="p-4 hover:bg-slate-50 transition">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <h4 class="font-semibold text-slate-800">{{ $user->name }}</h4>
                    <p class="text-xs text-slate-500 mt-1">{{ $user->email }}</p>
                </div>
                <span class="inline-flex px-2 py-1 text-xs font-bold leading-5 rounded-md 
                    {{ $user->role == 'super_admin' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $user->role == 'admin' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $user->role == 'guru' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $user->role == 'wali_kelas' ? 'bg-orange-100 text-orange-700' : '' }}
                    {{ $user->role == 'kepsek' ? 'bg-red-100 text-red-700' : '' }}
                ">
                    {{ ucwords(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="flex-1 px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition text-sm font-medium text-center">
                    Edit
                </a>
                @if(auth()->id() !== $user->id)
                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-medium">
                        Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
