@extends('layouts.medis')
@section('title', 'Scan Permintaan Obat')
@section('header', 'Scan Unit Permintaan Obat')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-10 text-center">
        <div class="mb-8">
            <div class="h-20 w-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Scan ID Card Pegawai</h2>
            <p class="text-gray-500 mt-2 text-lg">Arahkan scanner untuk memproses permintaan obat baru</p>
        </div>

        <form action="{{ route('medis.permintaan-obat.process') }}" method="POST" class="max-w-xl mx-auto flex flex-col gap-4">
            @csrf
            <input type="text" name="nik" id="scanner_input" autofocus autocomplete="off" placeholder="Scan di sini..." class="w-full px-8 py-5 text-center text-3xl font-bold rounded-2xl border-4 border-blue-500/30 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 outline-none text-gray-900 transition-all bg-gray-50 focus:bg-white placeholder-gray-300 shadow-sm">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-2xl text-lg transition-all shadow-md active:scale-[0.98]">
                Proses Pasien
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800 w-full md:w-auto">Riwayat Kunjungan & Permintaan Obat</h3>
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Waktu Kunjungan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Keluhan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Obat yang Diberikan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Petugas Medis</th>
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
                            <div class="text-blue-600">
                                {{ \Carbon\Carbon::parse($r->waktu_permintaan)->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($r->waktu_permintaan)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">
                                {{ $r->penyakit->nama_penyakit ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($r->details as $d)
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-[10px] border border-gray-200">
                                    {{ $d->obat->nama_obat }} ({{ $d->jumlah_diminta }})
                                </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-700">
                            {{ $r->tenagaMedis->nama_tenaga_medis ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">
                            Belum ada riwayat pada kriteria ini.
                        </td>
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