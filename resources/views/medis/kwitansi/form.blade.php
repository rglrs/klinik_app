@extends('layouts.medis')
@section('title', 'Form Penentuan Kwitansi')
@section('header', 'Input Data Rujukan Kwitansi')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
    <div class="mb-6 pb-6 border-b border-gray-100">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Informasi Pegawai</h3>
        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-xl">
            <div><span class="text-sm text-gray-500 block">NIK</span><span class="font-bold text-gray-900">{{ $pegawai->nik }}</span></div>
            <div><span class="text-sm text-gray-500 block">Nama Lengkap</span><span class="font-bold text-gray-900">{{ $pegawai->name }}</span></div>
            <div><span class="text-sm text-gray-500 block">Departemen</span><span class="font-semibold text-gray-800">{{ $pegawai->department->name ?? '-' }}</span></div>
            <div><span class="text-sm text-gray-500 block">Section</span><span class="font-semibold text-gray-800">{{ $pegawai->section->name ?? '-' }}</span></div>
        </div>
    </div>

    <form action="{{ route('medis.kwitansi.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="id_pegawai" value="{{ $pegawai->id }}">

        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">Tindakan / Status Perawatan Pasien</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all">
                    <input type="radio" name="status_perawatan" value="rawat_jalan" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 font-semibold text-gray-700">Rawat Jalan</span>
                </label>
                <label class="relative flex items-center justify-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all">
                    <input type="radio" name="status_perawatan" value="rawat_inap" required class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                    <span class="ml-3 font-semibold text-gray-700">Rawat Inap</span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
            <a href="{{ route('medis.kwitansi.scan') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-sm">Simpan Keputusan</button>
        </div>
    </form>
</div>
@endsection