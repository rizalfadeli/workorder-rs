@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-blue-500">
        <p class="text-xs text-gray-500">Total Pengguna</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $totalUsers }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-indigo-500">
        <p class="text-xs text-gray-500">Admin</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $totalAdmin }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-green-500">
        <p class="text-xs text-gray-500">Pelapor / Staf</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $totalUser }}</p>
    </div>
</div>

{{-- Filter & Pencarian --}}
<div class="bg-white rounded-xl shadow-sm p-5 mb-5">
    <form action="{{ route('admin.users.index') }}" method="GET">
        <div class="grid grid-cols-4 gap-3">

            {{-- Cari nama/email --}}
            <div class="col-span-2">
                <label class="block text-xs text-gray-500 mb-1">Cari Nama / Email</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Ketik nama atau email..."
                           class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-2.5 text-sm
                                  focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>

            {{-- Filter Unit --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Filter Unit / Poli</label>
                <select name="unit"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                               focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                    <option value="{{ $unit }}" {{ request('unit') === $unit ? 'selected' : '' }}>
                        {{ $unit }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Role --}}
            <div>
                <label class="block text-xs text-gray-500 mb-1">Filter Role</label>
                <select name="role"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm
                               focus:ring-2 focus:ring-blue-500 outline-none transition">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user"  {{ request('role') === 'user'  ? 'selected' : '' }}>Pelapor / Staf</option>
                </select>
            </div>

        </div>

        <div class="flex gap-2 mt-3">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-search mr-1"></i> Cari
            </button>
            @if(request()->hasAny(['search', 'unit', 'role']))
            <a href="{{ route('admin.users.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-times mr-1"></i> Reset
            </a>
            @endif
            <div class="flex-1"></div>
            <a href="{{ route('admin.users.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                <i class="fas fa-plus mr-1"></i> Tambah Pengguna
            </a>
        </div>
    </form>
</div>

{{-- Tabel --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">

    {{-- Info hasil pencarian --}}
    @if(request()->hasAny(['search', 'unit', 'role']))
    <div class="px-6 py-3 bg-blue-50 border-b border-blue-100 text-sm text-blue-700">
        <i class="fas fa-info-circle"></i>
        Menampilkan {{ $users->total() }} hasil
        @if(request('search')) untuk "<strong>{{ request('search') }}</strong>"@endif
        @if(request('unit')) di unit "<strong>{{ request('unit') }}</strong>"@endif
    </div>
    @endif

    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
            <tr>
                <th class="px-6 py-4 text-left">Nama</th>
                <th class="px-6 py-4 text-left">Email</th>
                <th class="px-6 py-4 text-left">Unit / Poli</th>
                <th class="px-6 py-4 text-center">Role</th>
                <th class="px-6 py-4 text-center">WO</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center
                                    justify-center font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                        @if($user->id === auth()->id())
                            <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Anda</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $user->unit ?? '-' }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                        {{ $user->role->name === 'admin'
                            ? 'bg-indigo-100 text-indigo-700'
                            : 'bg-green-100 text-green-700' }}">
                        {{ $user->role->label }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="font-semibold text-gray-700">
                        {{ $user->workOrders()->count() }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-white
                                  rounded-lg text-xs font-medium transition">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Hapus akun {{ $user->name }}? Semua data terkait akan ikut terhapus.')">
                            @csrf @method('DELETE')
                            <button class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white
                                           rounded-lg text-xs font-medium transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                    <div class="text-5xl mb-3">👤</div>
                    <p class="font-medium">Tidak ada pengguna ditemukan</p>
                    @if(request()->hasAny(['search', 'unit', 'role']))
                        <p class="text-sm mt-1">Coba ubah kata kunci pencarian.</p>
                    @endif
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}
            dari {{ $users->total() }} pengguna
        </p>
        {{ $users->links() }}
    </div>
</div>

@endsection