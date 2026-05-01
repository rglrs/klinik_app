@extends('layouts.app')
@section('title', 'Data Pegawai - Klinik JAI')
@section('header', 'Master Data Pegawai')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', sectionsData: {{ \Illuminate\Support\Js::from($sections) }}, formData: { nik: '', name: '', gender: 'Laki-laki', phone: '', department_id: '', section_id: '', position_id: '' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Pegawai PT JAI</h3>
                <p class="text-sm text-gray-500 mt-1">Data master seluruh pegawai untuk kebutuhan medis.</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('admin.pegawai.store') }}'; formData = { nik: '', name: '', gender: 'Laki-laki', phone: '', department_id: '', section_id: '', position_id: '' };" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah Pegawai
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NIK atau Nama Pegawai..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">NIK / NAMA</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Gender</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Departemen / Seksi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Posisi</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($pegawai as $p)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-900">{{ $p->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $p->nik }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $p->gender == 'Laki-laki' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                {{ $p->gender }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="text-gray-900 font-medium">{{ $p->department->name ?? '-' }}</div>
                            <div class="text-gray-500 text-xs mt-0.5">{{ $p->section->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $p->position->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/admin/pegawai/{{ $p->id }}'; formData = { nik: '{{ addslashes($p->nik) }}', name: '{{ addslashes($p->name) }}', gender: '{{ $p->gender }}', phone: '{{ addslashes($p->phone ?? '') }}', department_id: '{{ $p->department_id }}', section_id: '{{ $p->section_id }}', position_id: '{{ $p->position_id }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</button>
                            <form id="delete-form-{{ $p->id }}" action="{{ route('admin.pegawai.destroy', $p->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" @click="confirmDelete('delete-form-{{ $p->id }}')" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data pegawai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($pegawai->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $pegawai->links() }}
        </div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto" @click.stop>
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit Pegawai' : 'Tambah Pegawai'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                                <input type="text" name="nik" x-model="formData.nik" required placeholder="Masukkan NIK..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pegawai</label>
                                <input type="text" name="name" x-model="formData.name" required placeholder="Masukkan Nama..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="gender" x-model="formData.gender" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="phone" x-model="formData.phone" placeholder="Masukkan No. Telp..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                                <select name="department_id" x-model="formData.department_id" @change="formData.section_id = ''" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="">-- Pilih Departemen --</option>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Section</label>
                                <select name="section_id" x-model="formData.section_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="">-- Pilih Section --</option>
                                    <template x-for="s in sectionsData.filter(sec => sec.department_id == formData.department_id)" :key="s.id">
                                        <option :value="s.id" x-text="s.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Posisi</label>
                                <select name="position_id" x-model="formData.position_id" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                    <option value="">-- Pilih Posisi --</option>
                                    @foreach($positions as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
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