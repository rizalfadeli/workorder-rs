@extends('layouts.user')
@section('title', 'Dashboard')

@section('content')

{{-- Selamat Datang --}}
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-6 text-white mb-6">
    <h2 class="text-xl font-bold">Selamat datang, {{ auth()->user()->name }}! 🌻</h2>
    <p class="text-blue-100 text-sm mt-1">{{ auth()->user()->unit }} · {{ now()->format('l, d F Y') }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl p-4 shadow-sm border-t-4 border-blue-500">
        <p class="text-xs text-gray-500">Total WO Saya</p>
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
</div>

{{-- WO Terbaru --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Work Order Terbaru</h3>
        <div class="flex gap-3">
            <a href="{{ route('user.work-orders.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-xs font-medium transition">
                + Buat Laporan
            </a>
            <a href="{{ route('user.work-orders.index') }}"
               class="text-xs text-blue-600 hover:underline self-center">
                Lihat semua →
            </a>
        </div>
    </div>

    <div class="divide-y divide-gray-100">
        @forelse($recentOrders as $wo)
        @php
            $pColors = ['high'=>'red','medium'=>'yellow','low'=>'green'];
            $sColors = ['submitted'=>'yellow','in_progress'=>'blue','completed'=>'green','broken_total'=>'red'];
            $sLabels = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
        @endphp
        <div class="px-6 py-4 hover:bg-gray-50 transition flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-1 h-10 rounded-full bg-{{ $pColors[$wo->priority] }}-400"></div>
                <div>
                    <p class="font-medium text-gray-800">{{ $wo->item_name }}</p>
                    <p class="text-xs text-gray-400">{{ $wo->location }} · {{ $wo->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-2.5 py-1 rounded-full text-xs font-medium
                             bg-{{ $sColors[$wo->status] }}-100 text-{{ $sColors[$wo->status] }}-700">
                    {{ $sLabels[$wo->status] }}
                </span>
                <a href="{{ route('user.work-orders.show', $wo) }}"
                   class="text-xs text-blue-600 hover:underline">Detail</a>
            </div>
        </div>
        @empty
        <div class="px-6 py-12 text-center text-gray-400">
            <div class="text-5xl mb-3"></div>
            <p class="font-medium">Belum ada work order</p>
            <p class="text-sm mt-1">Klik "Buat Laporan" untuk melaporkan kerusakan.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection