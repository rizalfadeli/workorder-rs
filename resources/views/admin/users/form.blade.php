@extends('layouts.admin')
@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')
@section('page-title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
<div class="max-w-xl">

    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}"
           class="text-sm text-blue-600 hover:underline">← Kembali ke daftar pengguna</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-3 mb-6 pb-4 border-b">
            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center
                        justify-center font-bold text-xl">
                {{ $user->exists ? strtoupper(substr($user->name, 0, 1)) : '+' }}
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">
                    {{ $user->exists ? $user->name : 'Pengguna Baru' }}
                </h3>
                <p class="text-xs text-gray-400">
                    {{ $user->exists ? 'Edit data akun pengguna' : 'Isi data untuk membuat akun baru' }}
                </p>
            </div>
        </div>

        <form action="{{ $user->exists
                ? route('admin.users.update', $user)
                : route('admin.users.store') }}"
              method="POST">
            @csrf
            @if($user->exists) @method('PUT') @endif

            <div class="space-y-4">

                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $user->name) }}"
                           placeholder="Nama lengkap pengguna"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition
                                  @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email"
                           value="{{ old('email', $user->email) }}"
                           placeholder="email@rumahsakit.id"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition
                                  @error('email') border-red-400 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Password
                        @if(!$user->exists)
                            <span class="text-red-500">*</span>
                        @else
                            <span class="text-gray-400 font-normal">(kosongkan jika tidak diubah)</span>
                        @endif
                    </label>
                    <input type="password" name="password"
                           placeholder="{{ $user->exists ? 'Kosongkan jika tidak diubah' : 'Minimal 6 karakter' }}"
                           {{ !$user->exists ? 'required' : '' }}
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition
                                  @error('password') border-red-400 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                        @if(!$user->exists)<span class="text-red-500">*</span>@endif
                    </label>
                    <input type="password" name="password_confirmation"
                           placeholder="Ulangi password"
                           {{ !$user->exists ? 'required' : '' }}
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role_id" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                   focus:ring-2 focus:ring-blue-500 outline-none transition
                                   @error('role_id') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->label }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Unit / Poli --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Unit / Poli
                    </label>
                    <input type="text" name="unit"
                           value="{{ old('unit', $user->unit) }}"
                           placeholder="Contoh: Poli Jantung, ICU, Bangsal Bedah Lt.2"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <p class="text-xs text-gray-400 mt-1">
                        Isi sesuai nama poli atau unit tempat staf bertugas.
                    </p>
                </div>

            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 mt-6 pt-4 border-t">
                <a href="{{ route('admin.users.index') }}"
                   class="flex-1 text-center border border-gray-300 text-gray-700 py-2.5
                          rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5
                               rounded-lg text-sm font-medium transition">
                    {{ $user->exists ? 'Simpan Perubahan' : 'Buat Akun' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection