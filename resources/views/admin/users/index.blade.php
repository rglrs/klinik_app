@extends('layouts.app')
@section('title', 'Manajemen User - Klinik JAI')
@section('header', 'Manajemen User')

@section('content')
<div x-data="{ showModal: false, isEdit: false, formAction: '', formData: { name: '', email: '', role: 'admin' } }">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:justify-between md:items-center gap-4 bg-white">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Daftar Pengguna</h3>
                <p class="text-sm text-gray-500 mt-1">Kelola akses admin, dokter, dan perawat.</p>
            </div>
            <button @click="showModal = true; isEdit = false; formAction = '{{ route('admin.users.store') }}'; formData = { name: '', email: '', role: 'admin' };" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold transition-all shadow-md text-sm whitespace-nowrap">
                Tambah User
            </button>
        </div>

        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-gray-50/50">
            <form method="GET" class="flex items-center gap-2 w-full md:w-1/2">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..." class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none text-sm transition-colors" {{ request('search') ? 'autofocus' : '' }} onfocus="var val = this.value; this.value = ''; this.value = val;" oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)">
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
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Nama</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100">Role</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-100 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-gray-600 text-sm">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="showModal = true; isEdit = true; formAction = '/admin/users/{{ $user->id_user }}'; formData = { name: '{{ addslashes($user->name) }}', email: '{{ addslashes($user->email) }}', role: '{{ $user->role }}' };" class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors">Edit</button>
                            <form id="delete-form-{{ $user->id_user }}" action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                            <button type="button" @click="confirmDelete('delete-form-{{ $user->id_user }}')" class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 font-medium">Belum ada data user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <template x-teleport="body">
        <div>
            <div x-show="showModal" class="fixed inset-0 z-[100] bg-slate-900/50 backdrop-blur-sm" x-transition.opacity style="display: none;"></div>
            <div x-show="showModal" class="fixed inset-0 z-[110] flex items-center justify-center p-4" x-transition style="display: none;">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden" @click.stop>
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800" x-text="isEdit ? 'Edit User' : 'Tambah User'"></h3>
                        <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                    </div>
                    <form :action="formAction" method="POST" class="p-6">
                        @csrf
                        <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" x-model="formData.name" required placeholder="Masukkan nama lengkap..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" x-model="formData.email" required placeholder="Masukkan alamat email..." class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                        </div>
                        <div class="mb-5">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                            <select name="role" x-model="formData.role" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors bg-white">
                                <option value="admin">Admin</option>
                                <option value="dokter">Dokter</option>
                                <option value="perawat">Perawat</option>
                            </select>
                        </div>
                        <div class="mb-6" x-data="{ showPassword: false }">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span x-show="isEdit" class="text-gray-500 font-normal">(Kosongkan jika tidak diubah)</span></label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" name="password" x-bind:required="!isEdit" placeholder="Masukkan password..." class="w-full px-4 py-3 pr-10 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500 outline-none transition-colors">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg x-show="showPassword" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
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