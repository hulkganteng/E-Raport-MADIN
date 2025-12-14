<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Rapot Madin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .delay-100 { animation-delay: 0.1s; opacity: 0; }
        .delay-200 { animation-delay: 0.2s; opacity: 0; }
        .delay-300 { animation-delay: 0.3s; opacity: 0; }
        .delay-400 { animation-delay: 0.4s; opacity: 0; }
        .delay-500 { animation-delay: 0.5s; opacity: 0; }
        
        .input-focus-effect:focus {
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
        
        /* Subtle background pattern */
        .bg-pattern {
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(16, 185, 129, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 0% 100%, rgba(20, 184, 166, 0.1) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen bg-pattern flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Login Card -->
        <div class="bg-white rounded-3xl shadow-xl p-8 sm:p-10 border border-slate-100 animate-fade-in-up">
            
            <!-- Logo Section -->
            <div class="text-center mb-8 animate-fade-in-up delay-100">
                <div class="inline-block animate-float">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 p-1 shadow-lg shadow-emerald-100">
                        <img src="{{ asset('logo.jpg') }}" alt="Logo Madin" class="w-full h-full rounded-full object-cover">
                    </div>
                </div>
                <h1 class="mt-4 text-2xl font-bold text-slate-800">Rapot Digital</h1>
                <p class="text-sm text-emerald-600 font-semibold tracking-wide">Madrasah Diniyah Assyafi'iyah</p>
            </div>

            <!-- Welcome Text -->
            <div class="text-center mb-6 animate-fade-in-up delay-200">
                <h2 class="text-lg font-semibold text-slate-700">Selamat Datang! 👋</h2>
                <p class="text-sm text-slate-500 mt-1">Silakan masuk dengan akun Anda</p>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl animate-fade-in">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-700">Login Gagal</p>
                        @foreach($errors->all() as $error)
                            <p class="text-xs text-red-600 mt-1">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email -->
                <div class="animate-fade-in-up delay-300">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required 
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="input-focus-effect w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 text-sm transition-all duration-200 focus:outline-none focus:border-emerald-500"
                        >
                    </div>
                </div>
                
                <div class="animate-fade-in-up delay-400">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="input-focus-effect w-full pl-11 pr-12 py-3 rounded-xl border border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 text-sm transition-all duration-200 focus:outline-none focus:border-emerald-500"
                        >
                        <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-emerald-600 focus:outline-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                </div>

                <script>
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
                
                <!-- Remember Me -->
                <div class="flex items-center animate-fade-in-up delay-300">
                    <input type="checkbox" id="remember" name="remember" class="w-4 h-4 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500 cursor-pointer">
                    <label for="remember" class="ml-2 text-sm text-slate-600 cursor-pointer select-none">Ingat Saya</label>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-2 animate-fade-in-up delay-400">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold text-sm shadow-lg shadow-emerald-200 hover:shadow-emerald-300 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 transform hover:-translate-y-0.5 active:translate-y-0"
                    >
                        MASUK KE SISTEM
                    </button>
                </div>
            </form>
            
            <!-- Footer -->
            <div class="mt-8 text-center animate-fade-in delay-500">
                <p class="text-xs text-slate-400">&copy; {{ date('Y') }} Madrasah Diniyah Assyafi'iyah</p>
                <div class="mt-4">
                    <a href="{{ route('public.cek_nilai') }}" class="inline-flex items-center gap-2 text-emerald-700 text-sm font-semibold hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Cek Nilai Raport Santi ( Santri Only)
                    </a>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
