@extends('layouts.app')
@section('title', 'Tenaga Medis - Klinik JAI')
@section('header', 'Master Tenaga Medis')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', formData: { id_user: '', kode_tenaga_medis: '', nik: '', nama_tenaga_medis: '', jabatan: '' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Tenaga Medis</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data dokter dan perawat klinik.</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('admin.tenaga-medis.store') }}'; formData = { id_user: '', kode_tenaga_medis: '', nik: '', nama_tenaga_medis: '', jabatan: '' };" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah Tenaga Medis
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, Kode, atau NIK..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama / Jabatan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">KODE</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">NIK</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tenagaMedis as $t)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $t->nama_tenaga_medis }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $t->jabatan }}</div>
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $t->kode_tenaga_medis }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $t->nik }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/admin/tenaga-medis/{{ $t->id_tenaga_medis }}'; formData = { id_user: '{{ $t->id_user }}', kode_tenaga_medis: '{{ addslashes($t->kode_tenaga_medis) }}', nik: '{{ addslashes($t->nik) }}', nama_tenaga_medis: '{{ addslashes($t->nama_tenaga_medis) }}', jabatan: '{{ addslashes($t->jabatan) }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</button>
                            <form id="delete-form-{{ $t->id_tenaga_medis }}" action="{{ route('admin.tenaga-medis.destroy', $t->id_tenaga_medis) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" @click="confirmDelete('delete-form-{{ $t->id_tenaga_medis }}')" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data tenaga medis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($tenagaMedis->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $tenagaMedis->links() }}
        </div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit Tenaga Medis' : 'Tambah Tenaga Medis'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Akun User Terkait</label>
                                <select name="id_user" x-model="formData.id_user" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="">-- Pilih Akun Dokter/Perawat --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id_user }}">{{ $user->name }} ({{ $user->role }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Tenaga Medis</label>
                                <input type="text" name="kode_tenaga_medis" x-model="formData.kode_tenaga_medis" required placeholder="Masukkan Kode..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                                <input type="text" name="nik" x-model="formData.nik" required placeholder="Masukkan NIK..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Tenaga Medis</label>
                                <input type="text" name="nama_tenaga_medis" x-model="formData.nama_tenaga_medis" required placeholder="Masukkan Nama..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                                <input type="text" name="jabatan" x-model="formData.jabatan" required placeholder="Masukkan Jabatan..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
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