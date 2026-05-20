@extends('layouts.app')

@section('header', 'Tambah User Baru')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Role / Jabatan</label>
            <select name="role" required class="block w-full px-4 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm @error('role') border-red-500 @enderror">
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mapel</option>
                <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Staff Admin (Super Admin)</option>
            </select>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
            <div class="relative">
                <input type="password" name="password" required autocomplete="new-password" class="block w-full pl-4 pr-12 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm @error('password') border-red-500 @enderror">
                <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-teal-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
            <p class="text-xs text-slate-500 mt-1">Minimal 8 karakter. Kombinasi huruf dan angka disarankan.</p>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
            <div class="relative">
                <input type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full pl-4 pr-12 py-3 rounded-lg border-slate-300 bg-slate-50 focus:border-teal-500 focus:ring-teal-500 sm:text-sm">
                <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-teal-600 focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </button>
            </div>
             <p class="text-xs text-slate-500 mt-1">Ketik ulang password untuk konfirmasi.</p>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('users.index') }}" class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition font-semibold">Batal</a>
            <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition font-semibold shadow-lg shadow-teal-500/30">Simpan User</button>
        </div>
    </form>
</div>
<script>
    // Force clear password fields to prevent persistent browser autofill
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            let passFields = document.querySelectorAll('input[type="password"]');
            passFields.forEach(function(field) {
                field.value = '';
                field.setAttribute('readonly', 'readonly');
                field.addEventListener('focus', function() {
                    this.removeAttribute('readonly');
                });
                setTimeout(function(){ field.removeAttribute('readonly'); }, 500);
            });
        }, 300);
    });

    function togglePassword(btn) {
        let input = btn.parentElement.querySelector('input');
        if (input.type === "password") {
            input.type = "text";
            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>`;
        } else {
            input.type = "password";
            btn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
        }
    }
</script>
@endsection
