@extends('layouts.medis')
@section('title', 'Layanan ' . ucfirst($jenis))
@section('header', 'Layanan ' . ucfirst($jenis))

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-10 text-center">
        <div class="mb-8">
            <div class="h-20 w-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight uppercase">{{ $jenis }}</h2>
            <p class="text-gray-500 mt-2 text-lg">Scan barcode ID Card Karyawan untuk Check-in / Check-out</p>
        </div>

        <form action="{{ route('medis.layanan.store', $jenis) }}" method="POST" class="max-w-xl mx-auto flex flex-col gap-4">
            @csrf
            <input type="text" name="nik" id="scanner_input" autofocus autocomplete="off" placeholder="Arahkan Scanner Di Sini..." class="w-full px-8 py-5 text-center text-3xl font-bold rounded-2xl border-4 border-blue-500/30 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none text-gray-900 transition-all bg-gray-50 focus:bg-white placeholder-gray-300 shadow-sm">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl text-lg transition-all shadow-md active:scale-[0.98]">
                Proses Scanner
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <div class="flex items-center gap-3 w-full md:w-auto">
                <h3 class="text-lg font-bold text-gray-800">Riwayat Layanan {{ ucfirst($jenis) }}</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap">
                    Dokter: {{ $activeMedis->tenagaMedis->nama_tenaga_medis ?? 'Poli Umum' }}
                </span>
            </div>
            <form method="GET" id="filterForm" class="flex flex-col sm:flex-row w-full md:w-auto gap-2">
                <input type="date" name="tanggal" value="{{ request('tanggal', now()->format('Y-m-d')) }}" onchange="submitFilter()" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 outline-none cursor-pointer">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari NIK / Nama..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 outline-none w-full sm:w-48">
                <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition w-full sm:w-auto text-center flex items-center justify-center">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-center">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Pegawai</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Departemen / Section</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Check-In</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Check-Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $index => $r)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-center font-medium text-gray-400">
                            {{ $riwayat->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $r->pegawai->name ?? '-' }}</div>
                            <div class="text-sm text-gray-500">{{ $r->pegawai->nik ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-700">{{ $r->pegawai->department->name ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $r->pegawai->section->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            <div class="text-blue-600">{{ \Carbon\Carbon::parse($r->jam_masuk)->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($r->jam_masuk)->format('H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium">
                            @if($r->jam_keluar)
                                <div class="text-emerald-600">{{ \Carbon\Carbon::parse($r->jam_keluar)->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($r->jam_keluar)->format('H:i') }} WIB</div>
                            @else
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase animate-pulse">Aktif</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada riwayat pada kriteria ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
        <div class="p-6 border-t border-gray-50 bg-gray-50/30">
            {{ $riwayat->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scannerInput = document.getElementById('scanner_input');
        const searchInput = document.getElementById('searchInput');
        const filterForm = document.getElementById('filterForm');

        const lastScroll = sessionStorage.getItem('scrollPosition');
        if (lastScroll) {
            window.scrollTo(0, parseInt(lastScroll));
            sessionStorage.removeItem('scrollPosition');
        }

        window.submitFilter = function() {
            sessionStorage.setItem('scrollPosition', window.scrollY);
            if (filterForm) filterForm.submit();
        };

        if(scannerInput) {
            scannerInput.focus();
            document.addEventListener('click', (e) => {
                if(e.target.tagName !== 'INPUT' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
                    scannerInput.focus();
                }
            });
        }

        if (searchInput) {
            let debounceTimer;
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    window.submitFilter();
                }, 600);
            });
            
            searchInput.addEventListener('focus', function() {
                const val = this.value;
                this.value = '';
                this.value = val;
            });
        }
    });
</script>
@endsection