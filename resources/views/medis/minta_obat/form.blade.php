@extends('layouts.medis')
@section('title', 'Form Permintaan Obat')
@section('header', 'Input Permintaan Obat')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="mb-6 pb-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Pegawai</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <span class="text-sm text-gray-500 block">NIK</span>
                <span class="font-semibold text-gray-900">{{ $pegawai->nik }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500 block">Nama</span>
                <span class="font-semibold text-gray-900">{{ $pegawai->name }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500 block">Departemen</span>
                <span class="font-semibold text-gray-900">{{ $pegawai->department->name ?? '-' }}</span>
            </div>
            <div>
                <span class="text-sm text-gray-500 block">Section</span>
                <span class="font-semibold text-gray-900">{{ $pegawai->section->name ?? '-' }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('medis.permintaan-obat.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="id_pegawai" value="{{ $pegawai->id }}">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Keluhan / Penyakit</label>
            <select name="id_penyakit" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="">Pilih Penyakit...</option>
                @foreach($penyakit as $p)
                    <option value="{{ $p->id_penyakit }}">{{ $p->nama_penyakit }}</option>
                @endforeach
            </select>
        </div>

        <div class="pt-6 border-t border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <label class="block text-sm font-medium text-gray-700">Daftar Obat</label>
                <button type="button" onclick="addObatRow()" class="bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium hover:bg-blue-200 transition-colors text-sm">
                    + Tambah Obat
                </button>
            </div>
            
            <div id="obat_container" class="space-y-3">
                <div class="flex gap-4 items-start">
                    <div class="flex-1">
                        <select name="items[0][id_obat]" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Obat...</option>
                            @foreach($obat as $o)
                                <option value="{{ $o->id_obat }}">{{ $o->nama_obat }} (Stok: {{ $o->stok_saat_ini }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-32">
                        <input type="number" name="items[0][qty]" required min="1" placeholder="Jumlah" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="w-12 pt-2 text-center"></div>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
            <a href="{{ route('medis.permintaan-obat.scan') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">Simpan Data</button>
        </div>
    </form>
</div>

<script>
    let rowCount = 1;
    function addObatRow() {
        const container = document.getElementById('obat_container');
        const row = document.createElement('div');
        row.className = 'flex gap-4 items-start';
        row.innerHTML = `
            <div class="flex-1">
                <select name="items[${rowCount}][id_obat]" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih Obat...</option>
                    @foreach($obat as $o)
                        <option value="{{ $o->id_obat }}">{{ $o->nama_obat }} (Stok: {{ $o->stok_saat_ini }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-32">
                <input type="number" name="items[${rowCount}][qty]" required min="1" placeholder="Jumlah" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="w-12">
                <button type="button" onclick="this.closest('.flex').remove()" class="w-full py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors font-bold">X</button>
            </div>
        `;
        container.appendChild(row);
        rowCount++;
    }
</script>
@endsection