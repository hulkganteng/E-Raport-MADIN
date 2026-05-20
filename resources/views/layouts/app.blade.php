<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rapot Madin') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar-toggle:checked ~ #sidebar,
        #sidebar.is-open {
            transform: translateX(0);
        }

        #sidebar-overlay {
            opacity: 0;
            pointer-events: none;
        }

        #sidebar-toggle:checked ~ #sidebar-overlay,
        #sidebar-overlay.is-open {
            opacity: 1;
            pointer-events: auto;
        }

        body:has(#sidebar-toggle:checked),
        body.sidebar-open {
            overflow: hidden;
        }

        @media (min-width: 768px) {
            #sidebar {
                transform: translateX(0);
            }
        }
    </style>
    @stack('head')
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    <div class="min-h-screen flex relative">
        <input id="sidebar-toggle" type="checkbox" class="sr-only" aria-hidden="true">
        
        <!-- Mobile Sidebar Overlay -->
        <label id="sidebar-overlay" for="sidebar-toggle" class="fixed inset-0 bg-black/50 z-40 opacity-0 pointer-events-none md:hidden transition-opacity duration-300" aria-label="Tutup menu"></label>

        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-teal-900 text-white shadow-2xl h-screen fixed top-0 left-0 z-50 md:sticky transition-transform duration-300 ease-in-out flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-teal-800 flex items-center justify-between gap-3">
                 <div class="flex items-center gap-3 min-w-0">
                     <!-- Logo small in sidebar -->
                     <div class="w-8 h-8 rounded-full bg-white p-0.5 flex items-center justify-center shrink-0">
                        <img src="{{ asset('logo.jpg') }}" class="w-full h-full rounded-full object-cover">
                     </div>
                    <div class="overflow-hidden">
                        <h1 class="text-lg font-bold tracking-tight whitespace-nowrap">Rapot Madin</h1>
                        <p class="text-[10px] text-teal-300 uppercase tracking-widest whitespace-nowrap">Assyafi'iyah</p>
                        @if(isset($globalActivePeriode))
                        <div class="mt-1 px-2 py-0.5 bg-teal-800 rounded text-[10px] text-teal-100 font-mono">
                            {{ $globalActivePeriode->nama_periode }} ({{ $globalActivePeriode->semester }})
                        </div>
                        @else
                        <div class="mt-1 px-2 py-0.5 bg-red-800 rounded text-[10px] text-white font-mono animate-pulse">
                            NO ACTIVE PERIOD
                        </div>
                        @endif
                    </div>
                 </div>
                 <!-- Close button mobile -->
                 <label id="sidebar-close-btn" for="sidebar-toggle" role="button" tabindex="0" class="md:hidden text-teal-200 hover:text-white p-1 rounded-md hover:bg-teal-800 transition cursor-pointer" aria-label="Tutup menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                 </label>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('dashboard') ? 'bg-teal-800 text-white shadow-lg' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->role === 'super_admin')
                    <li class="px-4 py-2 text-xs font-bold text-teal-400 uppercase tracking-widest mt-6 mb-2">Master Data</li>
                    
                    <li>
                        <a href="{{ route('mapel.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('mapel.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Mata Pelajaran
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelas.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('kelas.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Data Kelas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kelas.manage_wali') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('kelas.manage_wali') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Atur Wali Kelas
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('santri.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('santri.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Data Santri
                        </a>
                    </li>
                    @if(isset($globalActivePeriode) && $globalActivePeriode->semester === 'genap')
                        <li>
                            <a href="{{ route('kenaikan.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('kenaikan.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Kenaikan & Kelulusan
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="{{ route('periode.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('periode.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tahun Ajar / Periode
                        </a>
                    </li>
                    @endif

                    <li class="px-4 py-2 text-xs font-bold text-teal-400 uppercase tracking-widest mt-6 mb-2">Akademik</li>
                    <li>
                        <a href="{{ route('nilai.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('nilai.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Input Nilai
                        </a>
                    </li>
                <!-- Menu Wali Kelas / Rekap (Only for assigned Wali Kelas or Admin) -->
                @php
                    $isWaliKelas = false;
                    if(auth()->user()->role == 'super_admin' || auth()->user()->role == 'wali_kelas') {
                        $isWaliKelas = true;
                    }
                    // Check dynamic assignment if role is just 'guru' but assigned as wali
                    if(!$isWaliKelas && isset($globalActivePeriode)) {
                        $isWaliKelas = \App\Models\WaliKelas::where('user_id', auth()->id())
                                        ->where('periode_id', $globalActivePeriode->id)
                                        ->exists();
                    }
                @endphp

                @if($isWaliKelas && auth()->user()->role !== 'super_admin')
                <li>
                    <a href="{{ route('kelas.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('rekap.*') || request()->routeIs('kelas.index') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Laporan Rapot
                    </a>
                </li>
                @endif

                {{-- <li>
                    <a href="{{ route('public.cek_nilai') }}" target="_blank" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 text-teal-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0A9 9 0 113 12a9 9 0 0118 0z"></path></svg>
                        Cek Nilai Publik
                    </a>
                </li> --}}
                     
                    <li class="px-4 py-2 text-xs font-bold text-teal-400 uppercase tracking-widest mt-6 mb-2">System</li>
                    @if(auth()->user()->role === 'super_admin')
                        <li>
                            <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('users.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                Manajemen User
                            </a>
                        </li>
                    @endif
                     
                    <li>
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-xl hover:bg-teal-800 transition flex items-center gap-3 {{ request()->routeIs('profile.*') ? 'bg-teal-800 text-white' : 'text-teal-100' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Edit Profil
                        </a>
                    </li>
                     
                    <li class="mt-10 border-t border-teal-800 pt-4">
                        <form method="POST" action="{{ route('logout') }}" class="px-2">
                             @csrf
                             <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-teal-200 hover:text-white hover:bg-red-800/50 rounded-xl transition">
                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                 Logout
                             </button>
                        </form>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col h-screen overflow-hidden w-full">
            <!-- Top Header -->
            <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-8 z-10 sticky top-0">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Button -->
                    <label id="sidebar-open-btn" for="sidebar-toggle" role="button" tabindex="0" class="md:hidden text-slate-500 hover:text-slate-700 p-2 rounded-lg bg-slate-100 hover:bg-slate-200 transition cursor-pointer" aria-controls="sidebar" aria-expanded="false" aria-label="Buka menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </label>
                    
                    <h2 class="text-xl font-bold text-slate-800 truncate">
                        @yield('header', 'Dashboard')
                    </h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden sm:block text-right leading-tight">
                        <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Guest' }}</p>
                        <span class="text-xs text-slate-500 uppercase">{{ Auth::user()->role ?? 'Admin' }}</span>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center text-white font-bold shadow-md shrink-0">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-auto p-4 sm:p-8 bg-slate-50 relative">
                 <!-- Background decoration -->
                 <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-teal-100/50 to-transparent pointer-events-none"></div>
                 
                 <div class="relative z-0 max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-800 rounded-lg shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                 </div>
            </div>
        </main>
    </div>

    <script>
        (function() {
            function initMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('sidebar-open-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');

            if (!sidebar || !toggle || !overlay || !openBtn || !closeBtn) {
                return;
            }

            function openSidebar() {
                toggle.checked = true;
                sidebar.classList.add('is-open');
                sidebar.setAttribute('data-state', 'open');
                openBtn.setAttribute('aria-expanded', 'true');
                overlay.classList.add('is-open');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                overlay.classList.add('opacity-100', 'pointer-events-auto');
                document.body.classList.add('sidebar-open');
            }

            function closeSidebar() {
                toggle.checked = false;
                sidebar.classList.remove('is-open');
                sidebar.setAttribute('data-state', 'closed');
                openBtn.setAttribute('aria-expanded', 'false');
                overlay.classList.remove('is-open');
                overlay.classList.add('opacity-0', 'pointer-events-none');
                overlay.classList.remove('opacity-100', 'pointer-events-auto');
                document.body.classList.remove('sidebar-open');
            }

            // Event Listeners
            openBtn.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                openSidebar();
            });
            closeBtn.addEventListener('click', function(event) {
                event.preventDefault();
                closeSidebar();
            });
            overlay.addEventListener('click', function(event) {
                event.preventDefault();
                closeSidebar();
            });

            toggle.addEventListener('change', function() {
                if (toggle.checked) {
                    openSidebar();
                } else {
                    closeSidebar();
                }
            });

            // Handle Resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    // Reset to default desktop state
                    toggle.checked = false;
                    sidebar.classList.remove('is-open');
                    sidebar.setAttribute('data-state', 'closed');
                    openBtn.setAttribute('aria-expanded', 'false');
                    overlay.classList.remove('is-open');
                    overlay.classList.add('opacity-0', 'pointer-events-none');
                    overlay.classList.remove('opacity-100', 'pointer-events-auto');
                    document.body.classList.remove('sidebar-open');
                }
            });
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initMobileSidebar);
            } else {
                initMobileSidebar();
            }
        })();
    </script>
    @yield('scripts')
</body>
</html>
