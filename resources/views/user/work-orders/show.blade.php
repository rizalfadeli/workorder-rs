@extends('layouts.user')
@section('title', 'Detail Work Order')

@section('content')

{{-- Header --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('user.work-orders.index') }}"
           class="text-sm text-blue-600 hover:underline">← Kembali</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-1">Detail Work Order</h2>
    </div>
    <a href="{{ route('user.work-orders.chat', $workOrder) }}"
       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">
        <i class="fas fa-comments"></i> Chat dengan Admin
    </a>
</div>

<div class="grid grid-cols-3 gap-6">

    {{-- Kolom Kiri: Info Utama --}}
    <div class="col-span-2 space-y-5">

        {{-- Info WO --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $workOrder->item_name }}</h3>
                    <p class="text-gray-400 font-mono text-sm mt-0.5">{{ $workOrder->code }}</p>
                </div>
                <div class="flex gap-2">
                    @php
                        $pBadge = ['high'=>'bg-red-100 text-red-700','medium'=>'bg-yellow-100 text-yellow-700','low'=>'bg-green-100 text-green-700'];
                        $sBadge = ['submitted'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','broken_total'=>'bg-red-100 text-red-700'];
                        $pLabels = ['high'=>'🔴 Tinggi','medium'=>'🟡 Sedang','low'=>'🟢 Rendah'];
                        $sLabels = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pBadge[$workOrder->priority] }}">
                        {{ $pLabels[$workOrder->priority] }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $sBadge[$workOrder->status] }}">
                        {{ $sLabels[$workOrder->status] }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-5">
                <div>
                    <p class="text-gray-400 mb-0.5">Lokasi / Unit</p>
                    <p class="font-medium text-gray-800">{{ $workOrder->location }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">Tanggal Lapor</p>
                    <p class="font-medium text-gray-800">{{ $workOrder->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">Teknisi</p>
                    <p class="font-medium text-gray-800">
                        {{ $workOrder->technician?->name ?? 'Belum ditentukan' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-400 mb-0.5">Estimasi Pengerjaan</p>
                    <p class="font-medium text-gray-800">
                        {{ $workOrder->estimated_days ? $workOrder->estimated_days . ' hari' : 'Belum ada estimasi' }}
                    </p>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-gray-400 text-sm mb-1">Deskripsi Kerusakan</p>
                <p class="text-gray-700 leading-relaxed">{{ $workOrder->description }}</p>
            </div>

            @if($workOrder->admin_notes)
            <div class="border-t pt-4 mt-4">
                <p class="text-gray-400 text-sm mb-1">Catatan Admin</p>
                <p class="text-gray-700 leading-relaxed">{{ $workOrder->admin_notes }}</p>
            </div>
            @endif
        </div>

        {{-- Foto Kerusakan --}}
        @if($workOrder->attachments->where('type', 'image')->count())
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h4 class="font-semibold text-gray-700 mb-4">📷 Foto Kerusakan</h4>
            <div class="grid grid-cols-3 gap-3">
                @foreach($workOrder->attachments->where('type', 'image') as $img)
                <a href="{{ Storage::url($img->file_path) }}" target="_blank">
                    <img src="{{ Storage::url($img->file_path) }}"
                         class="w-full h-32 object-cover rounded-xl border hover:opacity-80 transition cursor-pointer">
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- File PDF Berita Acara --}}
        @if($workOrder->attachments->where('type', 'pdf')->count())
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h4 class="font-semibold text-gray-700 mb-4">Berita Acara</h4>
            <div class="space-y-2">
                @foreach($workOrder->attachments->where('type', 'pdf') as $pdf)
                <a href="{{ Storage::url($pdf->file_path) }}" target="_blank"
                   class="flex items-center gap-3 p-3 border rounded-xl hover:bg-gray-50 transition">
                    <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $pdf->original_name }}</p>
                        <p class="text-xs text-gray-400">Klik untuk unduh</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Kolom Kanan: Riwayat Status --}}
    <div class="space-y-5">

        {{-- Progress Status --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-4">Progress</h4>
            <div class="space-y-3">
                @foreach(['submitted' => 'Diajukan', 'in_progress' => 'Diproses', 'completed' => 'Selesai'] as $s => $label)
                @php
                    $statusOrder = ['submitted' => 1, 'in_progress' => 2, 'completed' => 3, 'broken_total' => 4];
                    $currentOrder = $statusOrder[$workOrder->status] ?? 0;
                    $thisOrder = $statusOrder[$s] ?? 0;
                    $isDone = $currentOrder >= $thisOrder;
                @endphp
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                                {{ $isDone ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                        {{ $isDone ? '✓' : '' }}
                    </div>
                    <span class="text-sm {{ $isDone ? 'text-gray-800 font-medium' : 'text-gray-400' }}">
                        {{ $label }}
                    </span>
                </div>
                @endforeach

                @if($workOrder->status === 'broken_total')
                <div class="flex items-center gap-3">
                    <div class="w-7 h-7 rounded-full bg-red-500 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        !
                    </div>
                    <span class="text-sm text-red-600 font-medium">Rusak Total</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Status --}}
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-4">Riwayat</h4>
            <div class="space-y-3">
                @forelse($workOrder->statusLogs->sortByDesc('created_at') as $log)
                <div class="flex items-start gap-3 text-sm">
                    <div class="w-2 h-2 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-gray-700 font-medium">{{ $log->new_status }}</p>
                        @if($log->note)
                            <p class="text-gray-400 text-xs mt-0.5">{{ $log->note }}</p>
                        @endif
                        <p class="text-gray-300 text-xs mt-0.5">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </p>
                    </div>
                </div>
                @empty
                <p class="text-gray-400 text-sm">Belum ada riwayat.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection