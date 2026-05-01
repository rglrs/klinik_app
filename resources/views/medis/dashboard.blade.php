@extends('layouts.medis')
@section('title', 'Dashboard Medis - Klinik JAI')
@section('header', 'Dashboard Medis')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
        <div class="h-16 w-16 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold text-2xl">{{ $totalPasienHariIni }}</div>
        <div>
            <h4 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Total Konsultasi Hari Ini</h4>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPasienHariIni }} <span class="text-lg text-gray-500 font-medium">Pasien</span></p>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-5">
        <div class="h-16 w-16 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-2xl">{{ $dokterJaga->count() }}</div>
        <div>
            <h4 class="text-gray-500 text-sm font-semibold uppercase tracking-wider">Tenaga Medis Aktif (Standby)</h4>
            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $dokterJaga->count() }} <span class="text-lg text-gray-500 font-medium">Personel</span></p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Peringatan Stok Obat (Kritis)</h3>
        @if($obatKritis->count() > 0)
            <div class="space-y-3">
                @foreach($obatKritis as $obat)
                <div class="flex items-center justify-between p-4 bg-red-50 rounded-xl border border-red-100">
                    <div>
                        <h4 class="font-bold text-red-800">{{ $obat->nama_obat }}</h4>
                        <p class="text-xs text-red-600 mt-0.5">Sisa stok: {{ $obat->stok_saat_ini }} {{ $obat->satuan }}</p>
                    </div>
                    <a href="{{ route('medis.obat.index') }}" class="text-sm font-bold text-red-700 hover:underline">Restock</a>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500 font-medium bg-gray-50 rounded-xl border border-gray-100">Stok obat aman.</div>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-4 mb-4">Obat Mendekati Kadaluarsa (30 Hari)</h3>
        @if($obatExpired->count() > 0)
            <div class="space-y-3">
                @foreach($obatExpired as $obat)
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl border border-orange-100">
                    <div>
                        <h4 class="font-bold text-orange-800">{{ $obat->nama_obat }}</h4>
                        <p class="text-xs text-orange-600 mt-0.5">Exp: {{ \Carbon\Carbon::parse($obat->expired_date)->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('medis.obat.index') }}" class="text-sm font-bold text-orange-700 hover:underline">Kelola</a>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500 font-medium bg-gray-50 rounded-xl border border-gray-100">Tidak ada obat yang mendekati kadaluarsa.</div>
        @endif
    </div>
</div>
@endsection