@extends('layouts.app')
@section('title', 'Section - Klinik JAI')
@section('header', 'Master Section')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', formData: { name: '', department_id: '' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Section</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola data master section pegawai.</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('admin.sections.store') }}'; formData = { name: '', department_id: '' };" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah Section
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Section atau Departemen..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama Section</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Departemen</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($sections as $s)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $s->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $s->department->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/admin/sections/{{ $s->id }}'; formData = { name: '{{ addslashes($s->name) }}', department_id: '{{ $s->department_id }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</button>
                            <form id="delete-form-{{ $s->id }}" action="{{ route('admin.sections.destroy', $s->id) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" @click="confirmDelete('delete-form-{{ $s->id }}')" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data section.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($sections->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $sections->links() }}
        </div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.stop>
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit Section' : 'Tambah Section'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Departemen</label>
                            <select name="department_id" x-model="formData.department_id" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Section</label>
                            <input type="text" name="name" x-model="formData.name" required placeholder="Masukkan Nama Section..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
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