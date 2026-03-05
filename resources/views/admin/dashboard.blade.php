@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard Admin')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-blue-500">
        <p class="text-xs text-gray-500">Total WO</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['total'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-yellow-400">
        <p class="text-xs text-gray-500">Diajukan</p>
        <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $stats['submitted'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-indigo-500">
        <p class="text-xs text-gray-500">Diproses</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $stats['in_progress'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-green-500">
        <p class="text-xs text-gray-500">Selesai</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-red-500">
        <p class="text-xs text-gray-500">Rusak Total</p>
        <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['broken_total'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-orange-500">
        <p class="text-xs text-gray-500">Prioritas Tinggi</p>
        <p class="text-3xl font-bold text-orange-600 mt-1">{{ $stats['high_priority'] }}</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Tabel WO Urgent --}}
    <div class="col-span-2 bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Work Order Prioritas Tinggi</h3>
            <a href="{{ route('admin.work-orders.index', ['status' => 'submitted']) }}"
               class="text-xs text-blue-600 hover:underline">Lihat semua →</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Barang / Lokasi</th>
                    <th class="px-6 py-3 text-left">Pelapor</th>
                    <th class="px-6 py-3 text-center">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($urgentOrders as $wo)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800">{{ $wo->item_name }}</p>
                        <p class="text-xs text-gray-400">{{ $wo->location }}</p>
                    </td>
                    <td class="px-6 py-3 text-gray-600">{{ $wo->user->name }}</td>
                    <td class="px-6 py-3 text-center">
                        @php
                            $sColors = ['submitted'=>'yellow','in_progress'=>'blue','completed'=>'green','broken_total'=>'red'];
                            $sLabels = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
                            $sc = $sColors[$wo->status];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-{{ $sc }}-100 text-{{ $sc }}-700">
                            {{ $sLabels[$wo->status] }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <a href="{{ route('admin.work-orders.show', $wo) }}"
                           class="text-xs text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                        Tidak ada work order prioritas tinggi.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Info Singkat --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-4">Ringkasan</h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Teknisi Aktif</span>
                    <span class="font-bold text-gray-800">{{ $totalTechnicians }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">WO Belum Ditangani</span>
                    <span class="font-bold text-yellow-600">{{ $stats['submitted'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">WO Sedang Diproses</span>
                    <span class="font-bold text-indigo-600">{{ $stats['in_progress'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">WO Selesai</span>
                    <span class="font-bold text-green-600">{{ $stats['completed'] }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-3">🔗 Akses Cepat</h4>
            <div class="space-y-2">
                <a href="{{ route('admin.work-orders.index') }}"
                   class="flex items-center gap-2 text-sm text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                    <i class="fas fa-clipboard-list"></i> Semua Work Order
                </a>
                <a href="{{ route('admin.technicians.index') }}"
                   class="flex items-center gap-2 text-sm text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-lg transition">
                    <i class="fas fa-users"></i> Kelola Teknisi
                </a>
            </div>
        </div>
    </div>

</div>
@endsection