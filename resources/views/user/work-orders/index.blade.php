@extends('layouts.user')
@section('title', 'Work Order Saya')

@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Work Order Saya</h2>
    <a href="{{ route('user.work-orders.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5
              rounded-lg font-medium text-sm transition">
        + Buat Laporan
    </a>
</div>

<div class="space-y-4">
    @forelse($workOrders as $wo)
    @php
        $pColors   = ['high'=>'red','medium'=>'yellow','low'=>'green'];
        $pLabels   = ['high'=>'🔴 Tinggi','medium'=>'🟡 Sedang','low'=>'🟢 Rendah'];
        $sColors   = ['submitted'=>'yellow','in_progress'=>'blue','completed'=>'green','broken_total'=>'red'];
        $sLabels   = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
        $hasUnread = $wo->unread_count > 0;
    @endphp

    <div class="bg-white rounded-xl shadow-sm p-5 hover:shadow-md transition
                border-l-4 border-{{ $pColors[$wo->priority] }}-400">
        <div class="flex items-start justify-between gap-4">

            {{-- Info WO --}}
            <div class="flex items-start gap-3 flex-1">

                {{-- Titik indikator --}}
                <div class="mt-1.5 flex-shrink-0">
                    @if($hasUnread)
                        <span class="w-3 h-3 rounded-full bg-red-500 block animate-pulse"
                              title="Ada pesan baru dari admin"></span>
                    @else
                        <span class="w-3 h-3 rounded-full bg-gray-200 block"></span>
                    @endif
                </div>

                <div class="flex-1">
                    {{-- Nama & Badge --}}
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <h3 class="font-bold text-gray-800">{{ $wo->item_name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                     bg-{{ $pColors[$wo->priority] }}-100
                                     text-{{ $pColors[$wo->priority] }}-700">
                            {{ $pLabels[$wo->priority] }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                     bg-{{ $sColors[$wo->status] }}-100
                                     text-{{ $sColors[$wo->status] }}-700">
                            {{ $sLabels[$wo->status] }}
                        </span>
                        {{-- Badge pesan baru --}}
                        @if($hasUnread)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold
                                         bg-red-100 text-red-600 animate-pulse">
                                 {{ $wo->unread_count }} pesan baru
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500">
                        {{ $wo->location }} · {{ $wo->created_at->diffForHumans() }}
                    </p>

                    @if($wo->technician)
                        <p class="text-xs text-blue-600 mt-1">
                            <i class="fas fa-user-hard-hat"></i>
                            Teknisi: {{ $wo->technician->name }}
                            @if($wo->estimated_days)
                                · Est. {{ $wo->estimated_days }} hari
                            @endif
                        </p>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('user.work-orders.show', $wo) }}"
                   class="px-3 py-1.5 border border-blue-600 text-blue-600
                          hover:bg-blue-50 rounded-lg text-xs font-medium transition">
                    Detail
                </a>
                <a href="{{ route('user.work-orders.chat', $wo) }}"
                   class="relative inline-flex items-center gap-1.5 px-3 py-1.5
                          rounded-lg text-xs font-medium transition
                          {{ $hasUnread
                              ? 'bg-red-500 hover:bg-red-600 text-white'
                              : 'bg-indigo-500 hover:bg-indigo-600 text-white' }}">
                    <i class="fas fa-comments"></i>
                    <span>Chat</span>
                    @if($hasUnread)
                        <span class="ml-1 bg-white text-red-500 text-xs font-bold
                                     px-1.5 py-0.5 rounded-full leading-none">
                            {{ $wo->unread_count > 99 ? '99+' : $wo->unread_count }}
                        </span>
                    @endif
                </a>
            </div>

        </div>
    </div>

    @empty
    <div class="text-center py-16 bg-white rounded-xl shadow-sm">
        <div class="text-6xl mb-4">📭</div>
        <h3 class="text-lg font-semibold text-gray-700">Belum ada laporan</h3>
        <p class="text-gray-400 text-sm mt-1">
            Klik "Buat Laporan" untuk melaporkan kerusakan.
        </p>
    </div>
    @endforelse
</div>

<div class="mt-6">{{ $workOrders->links() }}</div>
{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- SUCCESS POPUP --}}
@if(session('success_data'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Laporan Berhasil Dikirim!',
    html: `
        <div style="text-align:left; font-size:14px">
            <p><b>No Work Order:</b> {{ session('success_data.code') }}</p>
            <p><b>Nama:</b> {{ session('success_data.nama') }}</p>
            <p><b>Lokasi:</b> {{ session('success_data.location') }}</p>
            <p><b>Item:</b> {{ session('success_data.item') }}</p>
            <p><b>Kategori:</b> {{ session('success_data.kategori') }}</p>
        </div>
    `,
    confirmButtonText: 'OK',
    confirmButtonColor: '#2563eb'
}).then(() => {
    window.location.href = "{{ route('user.work-orders.index') }}";
});
</script>
@endif

{{-- ERROR POPUP --}}
@if($errors->any())
<script>
Swal.fire({
    icon: 'error',
    title: 'Form Tidak Valid!',
    html: `
        <ul style="text-align:left;">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    `
});
</script>
@endif

@endsection