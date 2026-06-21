@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Laporan Semua Kunjungan Klinik</h2>
    </div>

    <form action="{{ route('admin.laporan.kunjungan') }}" method="GET" class="flex flex-wrap gap-4 mb-6 items-end bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama / NIK Pasien">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow transition">Filter</button>
            <a href="{{ route('admin.laporan.kunjungan.export', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">Export Excel</a>
        </div>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap table-auto">
            <thead>
                <tr class="text-left font-bold text-gray-700 bg-gray-100 border-b">
                    <th class="px-6 py-4">Tanggal Kunjungan</th>
                    <th class="px-6 py-4">NIK</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Keperluan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $item->tanggal }}</td>
                    <td class="px-6 py-4">{{ $item->nik }}</td>
                    <td class="px-6 py-4">{{ $item->pasien }}</td>
                    <td class="px-6 py-4 capitalize">{{ str_replace('-', ' ', $item->keperluan) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>
</div>
@endsection