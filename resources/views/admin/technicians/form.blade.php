@extends('layouts.admin')
@section('title', $technician->exists ? 'Edit Teknisi' : 'Tambah Teknisi')
@section('page-title', $technician->exists ? 'Edit Teknisi' : 'Tambah Teknisi')

@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-xl shadow-sm p-6">
        <form action="{{ $technician->exists
                ? route('admin.technicians.update', $technician)
                : route('admin.technicians.store') }}"
              method="POST">
            @csrf
            @if($technician->exists) @method('PUT') @endif

            <div class="space-y-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name', $technician->name) }}"
                           required placeholder="Nama lengkap teknisi"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition
                                  @error('name') border-red-400 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="phone"
                           value="{{ old('phone', $technician->phone) }}"
                           placeholder="Contoh: 08123456789"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keahlian</label>
                    <input type="text" name="specialty"
                           value="{{ old('specialty', $technician->specialty) }}"
                           placeholder="Contoh: Listrik, Mekanik, IT & Jaringan"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>

                @if($technician->exists)
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                           {{ old('is_active', $technician->is_active) ? 'checked' : '' }}
                           class="rounded border-gray-300">
                    <label for="is_active" class="text-sm text-gray-700">Teknisi Aktif</label>
                </div>
                @endif

            </div>

            <div class="flex gap-3 mt-6">
                <a href="{{ route('admin.technicians.index') }}"
                   class="flex-1 text-center border border-gray-300 text-gray-700 py-2.5
                          rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5
                               rounded-lg text-sm font-medium transition">
                    {{ $technician->exists ? 'Simpan Perubahan' : 'Tambah Teknisi' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection