@extends('layouts.medis')
@section('title', 'Master Penyakit - Klinik JAI')
@section('header', 'Master Data Penyakit')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', formData: { nama_penyakit: '', kode_icd: '', kategori: '', deskripsi: '' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Penyakit</h3>
                <p class="text-sm text-gray-500 mt-1">Referensi data penyakit</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('medis.penyakit.store') }}'; formData = { nama_penyakit: '', kode_icd: '', kategori: '', deskripsi: '' };" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah Penyakit
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama penyakit atau kode ICD..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = ''; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
            </form>
            <form method="GET" class="flex items-center gap-2 w-full md:w-auto">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <label class="text-sm text-gray-600 font-medium whitespace-nowrap">Tampilkan:</label>
                <select name="per_page" onchange="this.form.submit()" class="px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 outline-none text-sm bg-white transition-colors">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama Penyakit</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penyakit as $p)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->nama_penyakit }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/medis/penyakit/{{ $p->id_penyakit }}'; formData = { nama_penyakit: '{{ addslashes($p->nama_penyakit) }}', kode_icd: '{{ $p->kode_icd }}', kategori: '{{ $p->kategori }}', deskripsi: '{{ addslashes($p->deskripsi) }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</button>
                            <form action="{{ route('medis.penyakit.destroy', $p->id_penyakit) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($penyakit->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $penyakit->links() }}</div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.stop>
                    <div class="p-6 border-b flex justify-between items-center sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit Penyakit' : 'Tambah Penyakit'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Penyakit</label>
                            <input type="text" name="nama_penyakit" x-model="formData.nama_penyakit" required class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" @click="showModal = false" class="px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 font-semibold hover:bg-gray-50">Batal</button>
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection