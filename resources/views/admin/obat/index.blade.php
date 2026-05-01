@extends('layouts.app')
@section('title', 'Master Obat - Klinik JAI')
@section('header', 'Master Data Obat')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', formData: { nama_obat: '', nama_batch: '', satuan: '', jenis_obat: 'tablet', expired_date: '', stok_saat_ini: '', reorder_level: '' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Obat Klinik</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola stok dan ketersediaan obat.</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('admin.obat.store') }}'; formData = { nama_obat: '', nama_batch: '', satuan: '', jenis_obat: 'tablet', expired_date: '', stok_saat_ini: '', reorder_level: '' };" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah Obat
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama obat atau batch..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
            </form>
            <form method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <label class="text-sm text-gray-600 font-medium whitespace-nowrap">Tampilkan:</label>
                <select name="per_page" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm bg-white transition-colors">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 baris</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 baris</option>
                </select>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama Obat / Batch</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Jenis</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Stok</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Expired</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($obat as $o)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $o->nama_obat }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">Batch: {{ $o->nama_batch }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm capitalize text-gray-700">{{ $o->jenis_obat }} ({{ $o->satuan }})</td>
                        <td class="px-6 py-4 font-bold {{ $o->stok_saat_ini <= $o->reorder_level ? 'text-red-600' : 'text-gray-900' }}">{{ $o->stok_saat_ini }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Carbon\Carbon::parse($o->expired_date)->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/admin/obat/{{ $o->id_obat }}'; formData = { nama_obat: '{{ addslashes($o->nama_obat) }}', nama_batch: '{{ addslashes($o->nama_batch) }}', satuan: '{{ addslashes($o->satuan) }}', jenis_obat: '{{ $o->jenis_obat }}', expired_date: '{{ \Carbon\Carbon::parse($o->expired_date)->format('Y-m-d') }}', stok_saat_ini: '{{ $o->stok_saat_ini }}', reorder_level: '{{ $o->reorder_level }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</button>
                            <form id="delete-form-{{ $o->id_obat }}" action="{{ route('admin.obat.destroy', $o->id_obat) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" @click="confirmDelete('delete-form-{{ $o->id_obat }}')" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data obat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($obat->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $obat->links() }}
        </div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" @click.stop>
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit Obat' : 'Tambah Obat'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Obat</label>
                                <input type="text" name="nama_obat" x-model="formData.nama_obat" required placeholder="Masukkan nama obat..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Batch</label>
                                <input type="text" name="nama_batch" x-model="formData.nama_batch" required placeholder="Masukkan nama batch..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Satuan (Misal: Pcs, Strip)</label>
                                <input type="text" name="satuan" x-model="formData.satuan" required placeholder="Contoh: Pcs, Strip, Botol..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Obat</label>
                                <select name="jenis_obat" x-model="formData.jenis_obat" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="tablet">Tablet</option>
                                    <option value="kapsul">Kapsul</option>
                                    <option value="sirup">Sirup</option>
                                    <option value="salep">Salep</option>
                                    <option value="injeksi">Injeksi</option>
                                    <option value="tetes">Tetes</option>
                                    <option value="puyer">Puyer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Expired Date</label>
                                <input type="date" name="expired_date" x-model="formData.expired_date" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Stok Saat Ini</label>
                                    <input type="number" name="stok_saat_ini" x-model="formData.stok_saat_ini" required min="0" placeholder="0" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Batas Minimum</label>
                                    <input type="number" name="reorder_level" x-model="formData.reorder_level" required min="0" placeholder="0" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50 transition-colors">Batal</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection