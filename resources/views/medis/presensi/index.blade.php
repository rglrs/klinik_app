@extends('layouts.medis')
@section('title', 'Scan Presensi Tenaga Medis')
@section('header', 'Scan Presensi (Check-In / Check-Out)')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-10 text-center">
        <div class="mb-8">
            <div class="h-20 w-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Presensi Tenaga Medis</h2>
            <p class="text-gray-500 mt-2 text-lg">Scan barcode ID Card Anda pada kolom di bawah ini</p>
        </div>

        <form action="{{ route('medis.presensi.store') }}" method="POST" class="max-w-xl mx-auto flex flex-col gap-4">
            @csrf
            <input type="text" name="nik" id="scanner_input" autofocus autocomplete="off" placeholder="Arahkan Scanner Di Sini..." class="w-full px-8 py-5 text-center text-3xl font-bold rounded-2xl border-4 border-emerald-500/30 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 outline-none text-gray-900 transition-all bg-gray-50 focus:bg-white placeholder-gray-300 shadow-sm">
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl text-lg transition-all shadow-md active:scale-[0.98]">
                Proses Check-In / Check-Out
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-800 w-full md:w-auto">Riwayat Check-In / Check-Out</h3>
            <form method="GET" id="filterForm" class="flex flex-col sm:flex-row w-full md:w-auto gap-2">
                <input type="date" name="tanggal" value="{{ request('tanggal', now()->format('Y-m-d')) }}" onchange="submitFilter()" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 outline-none cursor-pointer">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" placeholder="Cari Nama / Kode..." class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-emerald-500 focus:border-emerald-500 outline-none w-full sm:w-48">
                <a href="{{ url()->current() }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-300 transition w-full sm:w-auto text-center flex items-center justify-center">Reset</a>
            </form>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama / Jabatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Kode Medis</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Waktu Masuk</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Waktu Keluar</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Status Jaga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $r->tenagaMedis->nama_tenaga_medis ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $r->tenagaMedis->jabatan ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $r->tenagaMedis->kode_tenaga_medis ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-emerald-600">{{ \Carbon\Carbon::parse($r->jam_masuk)->format('d M Y - H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-600">
                            {{ $r->jam_keluar ? \Carbon\Carbon::parse($r->jam_keluar)->format('d M Y - H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if(is_null($r->jam_keluar))
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">Aktif Berjaga</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada riwayat pada kriteria ini.</td></tr>
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