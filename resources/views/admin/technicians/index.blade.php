@extends('layouts.admin')
@section('title', 'Teknisi')
@section('page-title', 'Kelola Teknisi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <p class="text-gray-500 text-sm">Daftar seluruh teknisi yang terdaftar.</p>
    <a href="{{ route('admin.technicians.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
        + Tambah Teknisi
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4 text-left">Nama</th>
                <th class="px-6 py-4 text-left">No. HP</th>
                <th class="px-6 py-4 text-left">Keahlian</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($technicians as $tech)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $tech->name }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $tech->phone ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $tech->specialty ?? '-' }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $tech->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $tech->is_active ? 'Aktif' : 'Non-aktif' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.technicians.edit', $tech) }}"
                           class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white rounded-lg text-xs transition">
                            Edit
                        </a>
                        <form action="{{ route('admin.technicians.destroy', $tech) }}" method="POST"
                              onsubmit="return confirm('Hapus teknisi ini?')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs transition">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-users text-4xl mb-2 block"></i>
                    Belum ada teknisi terdaftar.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">{{ $technicians->links() }}</div>
</div>
@endsection