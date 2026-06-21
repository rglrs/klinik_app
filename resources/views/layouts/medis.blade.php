<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal Medis - Klinik JAI')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden text-gray-900" 
    x-data="{ sidebarOpen: window.innerWidth >= 768 }"
    @resize.window="if(window.innerWidth < 768) sidebarOpen = false">

    <div x-show="sidebarOpen" x-transition.opacity @click="sidebarOpen = false" class="fixed inset-0 bg-emerald-950/60 z-20 md:hidden" style="display: none;"></div>

    <aside :class="sidebarOpen ? 'translate-x-0 w-72' : '-translate-x-full w-72 md:translate-x-0 md:w-20'"
        class="fixed md:relative z-30 transition-all duration-300 bg-emerald-950 text-white flex flex-col h-full shadow-2xl">
        <div class="h-20 flex items-center justify-between px-6 border-b border-emerald-900">
            <h1 x-show="sidebarOpen" x-transition
                class="text-2xl font-bold tracking-wider text-emerald-400 whitespace-nowrap">Klinik JAI</h1>
            <button @click="sidebarOpen = !sidebarOpen" class="text-emerald-300 hover:text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1.5 scrollbar-hide">
            <a href="{{ route('medis.dashboard') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.dashboard') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Dashboard Medis</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Aktivitas Tenaga Medis</p>
            </div>
            <a href="{{ route('medis.presensi.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.presensi.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Scan Presensi Medis</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Layanan Pasien</p>
            </div>
            <a href="{{ route('medis.konsultasi.scan') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.konsultasi.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Konsultasi Dokter</span>
            </a>
            <a href="{{ route('medis.layanan.index', 'sakit') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->is('medis/layanan/sakit') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Istirahat Sakit</span>
            </a>
            <a href="{{ route('medis.layanan.index', 'hamil') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->is('medis/layanan/hamil') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Istirahat Hamil</span>
            </a>
            <a href="{{ route('medis.layanan.index', 'laktasi') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->is('medis/layanan/laktasi') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Laktasi</span>
            </a>
            <a href="{{ route('medis.permintaan-obat.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.permintaan-obat.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Permintaan Obat</span>
            </a>
            <a href="{{ route('medis.kwitansi.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.kwitansi.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Penyerahan Kwitansi</span>
            </a>

            <div class="pt-4 pb-2 px-3" x-show="sidebarOpen">
                <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Farmasi & Master</p>
            </div>
            <a href="{{ route('medis.obat.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.obat.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Master Obat</span>
            </a>
            <a href="{{ route('medis.penyakit.index') }}"
                class="flex items-center gap-4 px-3 py-3 rounded-xl hover:bg-emerald-900 transition-all font-medium {{ request()->routeIs('medis.penyakit.*') ? 'bg-emerald-600 text-white shadow-md' : 'text-emerald-100' }}">
                <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span x-show="sidebarOpen" class="whitespace-nowrap">Master Penyakit</span>
            </a>
        </nav>
        <div class="p-3 border-t border-emerald-900 bg-emerald-950">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 px-3 py-3 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all font-semibold">
                    <svg class="h-6 w-6 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
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
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-600 hover:text-emerald-900 focus:outline-none">
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
                <div class="h-9 w-9 md:h-10 md:w-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold border border-emerald-200 shrink-0">
                    {{ substr(auth()->user()->name ?? 'M', 0, 1) }}
                </div>
            </div>
        </header>

        <div id="toast-container" class="fixed top-4 right-4 left-4 sm:left-auto sm:top-6 sm:right-6 z-[9999] flex flex-col gap-3 w-auto sm:w-full sm:max-w-sm pointer-events-none">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-12"
                    class="pointer-events-auto bg-white border-l-4 border-emerald-500 rounded-xl shadow-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 text-xl ml-4">&times;</button>
                </div>
            @endif

            @if(session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-12"
                    class="pointer-events-auto bg-white border-l-4 border-red-500 rounded-xl shadow-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-100 p-2 rounded-full">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <span class="text-sm font-bold text-gray-800">{{ session('error') ?? $errors->first() }}</span>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 text-xl ml-4">&times;</button>
                </div>
            @endif
        </div>
        <div class="flex-1 overflow-y-auto p-4 md:p-8 scrollbar-hide">
            @yield('content')
        </div>
    </main>
</body>
</html>