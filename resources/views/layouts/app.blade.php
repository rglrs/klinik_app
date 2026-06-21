<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik PT JAI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden text-gray-900" 
    x-data="{ 
        sidebarOpen: window.innerWidth >= 768, 
        deleteModalOpen: false, 
        deleteFormId: null,
        confirmDelete(id) {
            this.deleteFormId = id;
            this.deleteModalOpen = true;
        }
    }"
    @resize.window="if(window.innerWidth < 768) sidebarOpen = false">

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-slate-900/60 z-20 md:hidden" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full w-72 md:translate-x-0 md:w-20'"
        class="fixed md:relative z-30 transition-all duration-300 bg-slate-900 text-white flex flex-col h-full shadow-2xl">
        <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/60">
            <h1 x-show="sidebarOpen" x-transition
                class="text-2xl font-bold tracking-wider bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent whitespace-nowrap">
                Klinik JAI</h1>
            <button @click="sidebarOpen = !sidebarOpen" class="text-slate-300 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1.5 scrollbar-hide">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Master Data</p>
            </div>

            <a href="{{ route('admin.users.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">User Management</span>
            </a>
            <a href="{{ route('admin.pegawai.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.pegawai.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Pegawai</span>
            </a>
            <a href="{{ route('admin.tenaga-medis.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.tenaga-medis.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Tenaga Medis</span>
            </a>
            <a href="{{ route('admin.obat.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.obat.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Master Obat</span>
            </a>
            <a href="{{ route('admin.penyakit.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.penyakit.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Master Penyakit</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Struktur Organisasi</p>
            </div>

            <a href="{{ route('admin.departments.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.departments.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Departemen</span>
            </a>
            <a href="{{ route('admin.sections.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.sections.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Section</span>
            </a>
            <a href="{{ route('admin.positions.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.positions.*') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Posisi</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Laporan & Rekapitulasi</p>
            </div>

            <a href="{{ route('admin.laporan.presensi-tenaga-medis') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.presensi-tenaga-medis') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Presensi Dokter/Medis</span>
            </a>

            <a href="{{ route('admin.laporan.istirahat-sakit') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.istirahat-sakit') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Istirahat Sakit</span>
            </a>

            <a href="{{ route('admin.laporan.istirahat-hamil') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.istirahat-hamil') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Istirahat Hamil</span>
            </a>

            <a href="{{ route('admin.laporan.laktasi') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.laktasi') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Laktasi</span>
            </a>

            <a href="{{ route('admin.laporan.konsultasi') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.konsultasi') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Konsultasi Dokter</span>
            </a>

            <a href="{{ route('admin.laporan.permintaan-obat') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.permintaan-obat') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Permintaan Obat</span>
            </a>

            <a href="{{ route('admin.laporan.kwitansi') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.kwitansi') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Kwitansi</span>
            </a>

            <a href="{{ route('admin.laporan.kunjungan') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-slate-800 transition-all font-medium {{ request()->routeIs('admin.laporan.kunjungan') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-300' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Semua Kunjungan</span>
            </a>
        </nav>

        <div class="p-3 border-t border-slate-800/60 bg-slate-900/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-3 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all font-semibold">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="sidebarOpen" class="whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-full bg-slate-50 relative z-10 overflow-hidden w-full">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-4 md:px-8 z-10 shadow-sm relative w-full">
            <div class="flex items-center gap-3 md:gap-4 truncate">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h2 class="text-lg md:text-xl font-bold text-gray-800 truncate">@yield('header')</h2>
            </div>
            <div class="flex items-center gap-3 md:gap-4 ml-2">
                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'User' }}</span>
                    <span class="text-xs font-medium text-gray-500 uppercase">{{ auth()->user()->role ?? 'Role' }}</span>
                </div>
                <div class="h-9 w-9 md:h-10 md:w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200 shrink-0">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-8 scrollbar-hide">
            @yield('content')
        </div>
    </main>

    <div id="delete-modal" x-show="deleteModalOpen" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" @click="deleteModalOpen = false">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;
            <div x-show="deleteModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-bold text-gray-900">Hapus Data?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data ini secara permanen? Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-8 sm:flex sm:flex-row-reverse gap-3">
                    <button type="button" @click="document.getElementById(deleteFormId).submit()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-bold text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm transition-all">Hapus Sekarang</button>
                    <button type="button" @click="deleteModalOpen = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm transition-all">Batalkan</button>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed top-4 right-4 left-4 sm:left-auto sm:top-5 sm:right-5 z-[9999] flex flex-col gap-3 w-auto sm:w-full sm:max-w-xs pointer-events-none">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="pointer-events-auto bg-white border-l-4 border-green-500 rounded-xl shadow-2xl p-4 flex items-center justify-between animate-slide-in">
                <div class="flex items-center gap-3">
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-800">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif
        @if(session('error') || $errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                class="pointer-events-auto bg-white border-l-4 border-red-500 rounded-xl shadow-2xl p-4 flex items-center justify-between animate-slide-in">
                <div class="flex items-center gap-3">
                    <div class="bg-red-100 p-2 rounded-full">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <span class="text-sm font-bold text-gray-800">{{ session('error') ?? $errors->first() }}</span>
                </div>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif
    </div>

    <style>
        @keyframes slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .animate-slide-in { animation: slide-in 0.3s ease-out; }
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>