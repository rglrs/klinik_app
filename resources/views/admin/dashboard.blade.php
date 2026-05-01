@extends('layouts.app')
@section('title', 'Dashboard - Klinik JAI')
@section('header', 'Dashboard Admin')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="h-14 w-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xl">{{ $stats['pegawai'] }}</div>
        <div><h4 class="text-gray-500 text-sm font-semibold">Total Pegawai</h4><p class="text-2xl font-bold text-gray-800">{{ $stats['pegawai'] }}</p></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="h-14 w-14 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600 font-bold text-xl">{{ $stats['obat'] }}</div>
        <div><h4 class="text-gray-500 text-sm font-semibold">Total Jenis Obat</h4><p class="text-2xl font-bold text-gray-800">{{ $stats['obat'] }}</p></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="h-14 w-14 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xl">{{ $stats['tenaga_medis'] }}</div>
        <div><h4 class="text-gray-500 text-sm font-semibold">Tenaga Medis</h4><p class="text-2xl font-bold text-gray-800">{{ $stats['tenaga_medis'] }}</p></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="h-14 w-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-600 font-bold text-xl">{{ $stats['penyakit'] }}</div>
        <div><h4 class="text-gray-500 text-sm font-semibold">Master Penyakit</h4><p class="text-2xl font-bold text-gray-800">{{ $stats['penyakit'] }}</p></div>
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
                        <p class="text-xs text-red-600 mt-0.5">Sisa stok: {{ $obat->stok_saat_ini }} {{ $obat->satuan }} (Min: {{ $obat->reorder_level }})</p>
                    </div>
                    <a href="{{ route('admin.obat.index') }}" class="text-sm font-bold text-red-700 hover:underline">Perbarui Stok</a>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500 font-medium bg-gray-50 rounded-xl border border-gray-100">Stok obat dalam kondisi aman.</div>
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
                    <a href="{{ route('admin.obat.index') }}" class="text-sm font-bold text-orange-700 hover:underline">Kelola Obat</a>
                </div>
                @endforeach
            </div>
        @else
            <div class="p-6 text-center text-gray-500 font-medium bg-gray-50 rounded-xl border border-gray-100">Tidak ada obat yang mendekati kadaluarsa.</div>
        @endif
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-lg font-bold text-gray-800">Statistik Kunjungan Klinik</h3>
        <form method="GET" class="w-full sm:w-auto">
            <select name="filter" onchange="this.form.submit()" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="daily" {{ $filter === 'daily' ? 'selected' : '' }}>Harian (14 Hari Terakhir)</option>
                <option value="monthly" {{ $filter === 'monthly' ? 'selected' : '' }}>Bulanan (Tahun Ini)</option>
                <option value="yearly" {{ $filter === 'yearly' ? 'selected' : '' }}>Tahunan (5 Tahun Terakhir)</option>
            </select>
        </form>
    </div>
    <div class="relative h-80 w-full">
        <canvas id="kunjunganChart"></canvas>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('kunjunganChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Total Kunjungan Medis',
                    data: chartData,
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: 'sans-serif' },
                        bodyFont: { size: 14, weight: 'bold', family: 'sans-serif' },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { family: 'sans-serif' } }
                    },
                    y: {
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#64748b', font: { family: 'sans-serif' }, padding: 10, stepSize: 1 },
                        beginAtZero: true
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });
    });
</script>
@endsection