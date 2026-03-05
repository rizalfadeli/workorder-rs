@extends('layouts.admin')
@section('title', 'Work Orders')
@section('page-title', 'Antrian Work Order')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @foreach([
        ['label' => 'Total',       'key' => 'total',        'color' => 'blue'],
        ['label' => 'Diajukan',    'key' => 'submitted',    'color' => 'yellow'],
        ['label' => 'Diproses',    'key' => 'in_progress',  'color' => 'indigo'],
        ['label' => 'Selesai',     'key' => 'completed',    'color' => 'green'],
        ['label' => 'Rusak Total', 'key' => 'broken_total', 'color' => 'red'],
    ] as $stat)
    <div class="bg-white rounded-xl p-4 shadow-sm border border-{{ $stat['color'] }}-100">
        <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>
        <p class="text-2xl font-bold text-{{ $stat['color'] }}-600 mt-1">
            {{ $stats[$stat['key']] }}
        </p>
    </div>
    @endforeach
</div>

{{-- Filter Status --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-4 flex gap-3 flex-wrap">
    <a href="{{ route('admin.work-orders.export.completed') }}"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
        Export Laporan Selesai (Excel)
    </a>

    @foreach([
        ''             => 'Semua',
        'submitted'    => 'Diajukan',
        'in_progress'  => 'Diproses',
        'completed'    => 'Selesai',
        'broken_total' => 'Rusak Total',
    ] as $val => $label)
    <a href="{{ route('admin.work-orders.index', $val ? ['status' => $val] : []) }}"
       class="px-4 py-2 rounded-lg text-sm font-medium transition
              {{ request('status') === $val || (!request('status') && !$val)
                  ? 'bg-blue-600 text-white'
                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- Tabel Work Order --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-4 text-left">Kode / Barang</th>
                <th class="px-6 py-4 text-left">Pelapor / Unit</th>
                <th class="px-6 py-4 text-center">Prioritas</th>
                <th class="px-6 py-4 text-center">Status</th>
                <th class="px-6 py-4 text-left">Teknisi</th>
                <th class="px-6 py-4 text-center">Chat</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($workOrders as $wo)
            @php
                $pColors   = ['high'=>'red','medium'=>'yellow','low'=>'green'];
                $pLabels   = ['high'=>'🔴 Tinggi','medium'=>'🟡 Sedang','low'=>'🟢 Rendah'];
                $sColors   = ['submitted'=>'yellow','in_progress'=>'blue','completed'=>'green','broken_total'=>'red'];
                $sLabels   = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
                $hasUnread = $wo->unread_count > 0;
            @endphp
            <tr class="hover:bg-gray-50 transition">

                {{-- Kode & Nama Barang --}}
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        @if($hasUnread)
                            <span class="w-2.5 h-2.5 rounded-full bg-red-500 flex-shrink-0 animate-pulse"
                                  title="Ada pesan belum dibaca"></span>
                        @else
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-200 flex-shrink-0"></span>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-800">{{ $wo->item_name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $wo->code }}</p>
                        </div>
                    </div>
                </td>

                {{-- Pelapor --}}
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">{{ $wo->nama_pelapor ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $wo->location ?? '-' }}</p>
                </td>

                {{-- Prioritas --}}
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                 bg-{{ $pColors[$wo->priority] }}-100
                                 text-{{ $pColors[$wo->priority] }}-700">
                        {{ $pLabels[$wo->priority] }}
                    </span>
                </td>

                {{-- Status --}}
                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                 bg-{{ $sColors[$wo->status] }}-100
                                 text-{{ $sColors[$wo->status] }}-700">
                        {{ $sLabels[$wo->status] }}
                    </span>
                </td>

                {{-- Teknisi --}}
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $wo->technician?->name ?? '—' }}
                </td>

                {{-- Tombol Chat + Badge unread --}}
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.work-orders.chat', $wo) }}"
                       class="relative inline-flex items-center gap-1.5 px-3 py-1.5
                              rounded-lg text-xs font-medium transition
                              {{ $hasUnread
                                  ? 'bg-red-500 hover:bg-red-600 text-white'
                                  : 'bg-indigo-100 hover:bg-indigo-200 text-indigo-700' }}">
                        <i class="fas fa-comments"></i>
                        <span>Chat</span>
                        @if($hasUnread)
                            <span class="ml-1 bg-white text-red-500 text-xs font-bold
                                         px-1.5 py-0.5 rounded-full leading-none">
                                {{ $wo->unread_count > 99 ? '99+' : $wo->unread_count }}
                            </span>
                        @endif
                    </a>
                </td>

                {{-- Detail --}}
                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.work-orders.show', $wo) }}"
                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white
                              rounded-lg text-xs transition">
                        Detail
                    </a>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2 block"></i>
                    Tidak ada work order.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="px-6 py-4 border-t flex items-center justify-between">
        <p class="text-xs text-gray-400">
            Menampilkan {{ $workOrders->firstItem() ?? 0 }}–{{ $workOrders->lastItem() ?? 0 }}
            dari {{ $workOrders->total() }} work order
        </p>
        {{ $workOrders->links() }}
    </div>
</div>

<audio id="notifSound">
    <source src="https://actions.google.com/sounds/v1/alarms/alarm_clock.ogg" type="audio/ogg">
</audio>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const sound = document.getElementById('notifSound');
    let alerted = {}; // Track WO yang sudah muncul alert

    function checkUnread() {
        fetch("{{ route('admin.unread-count') }}")
            .then(res => res.json())
            .then(data => {
                if (!data.work_orders) return;

                data.work_orders.forEach(wo => {
                    // Hanya alert jika unread_count >= 2
                    if (wo.unread_count >= 2) {
                        if (!alerted[wo.id]) {
                            alerted[wo.id] = true;

                            sound.play().catch(() => {});

                            Swal.fire({
                                title: '🚨 DOUBLE CHAT TERDETEKSI!',
                                html: `
                                    <div style="font-size:18px;margin-top:10px;">
                                        Work Order <b>${wo.code}</b><br>
                                        memiliki <b>${wo.unread_count}</b> pesan belum dibaca.
                                    </div>
                                `,
                                icon: 'warning',
                                confirmButtonText: 'Buka Chat',
                                confirmButtonColor: '#dc2626'
                            }).then(result => {
                                if (result.isConfirmed) {
                                    window.location.href = `/admin/work-orders/${wo.id}/chat`;
                                }
                            });
                        }
                    } else {
                        // Jika unread_count turun < 2, hapus alert tracking
                        delete alerted[wo.id];
                    }
                });
            });
    }

    // Cek setiap 5 detik
    setInterval(checkUnread, 5000);
});
</script>

@endsection