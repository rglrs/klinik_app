@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Laporan Presensi Tenaga Medis</h2>
    </div>

    <form action="{{ route('admin.laporan.presensi-tenaga-medis') }}" method="GET" class="flex flex-wrap gap-4 mb-6 items-end bg-white p-4 rounded shadow">
        <div>
            <label class="block text-sm font-medium text-gray-700">Pencarian</label>
            <input type="text" name="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama / NIK">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
            <select name="keterangan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Semua</option>
                <option value="hadir" {{ request('keterangan') == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ request('keterangan') == 'izin' ? 'selected' : '' }}>Izin</option>
            </select>
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
            <a href="{{ route('admin.laporan.presensi-tenaga-medis.export', request()->all()) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">Export Excel</a>
        </div>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full whitespace-no-wrap table-auto">
            <thead>
                <tr class="text-left font-bold text-gray-700 bg-gray-100 border-b">
                    <th class="px-6 py-4">Tanggal</th>
                    <th class="px-6 py-4">NIK</th>
                    <th class="px-6 py-4">Nama Tenaga Medis</th>
                    <th class="px-6 py-4">Keterangan</th>
                    <th class="px-6 py-4">Jam Masuk</th>
                    <th class="px-6 py-4">Jam Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $item->created_at->format('Y-m-d H:i') }}</td>
                    <td class="px-6 py-4">{{ $item->tenagaMedis->nik }}</td>
                    <td class="px-6 py-4">{{ $item->tenagaMedis->nama_tenaga_medis }}</td>
                    <td class="px-6 py-4 capitalize">{{ $item->keterangan }}</td>
                    <td class="px-6 py-4">{{ $item->jam_masuk }}</td>
                    <td class="px-6 py-4">{{ $item->jam_keluar ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan</td>
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