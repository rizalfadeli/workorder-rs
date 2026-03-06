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

{{-- SEARCH (Tambahan) --}}
<div class="bg-white rounded-xl shadow-sm p-4 mb-4">
    <input 
        type="text"
        id="searchWO"
        placeholder="🔎 Cari kode WO, barang, pelapor, lokasi..."
        class="w-full border rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
</div>

{{-- Tabel Work Order --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm" id="woTable">
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

            <tr class="hover:bg-gray-50 transition wo-row">

                <td class="px-6 py-4 wo-search">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            @if($hasUnread)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full animate-pulse z-10">
                                    {{ $wo->unread_count }}
                                </span>
                                <span class="w-3 h-3 rounded-full bg-red-500 animate-ping absolute inset-0"></span>
                                <span class="w-3 h-3 rounded-full bg-red-500 relative"></span>
                            @else
                                <span class="w-3 h-3 rounded-full bg-gray-200"></span>
                            @endif
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 leading-tight">{{ $wo->item_name }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $wo->code }}</p>
                        </div>
                    </div>
                </td>

                <td class="px-6 py-4 wo-search">
                    <p class="font-medium text-gray-800">{{ $wo->nama_pelapor ?? '-' }}</p>
                    <p class="text-xs text-gray-400">{{ $wo->location ?? '-' }}</p>
                </td>

                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-bold
                                 bg-{{ $pColors[$wo->priority] }}-100
                                 text-{{ $pColors[$wo->priority] }}-700">
                        {{ $pLabels[$wo->priority] }}
                    </span>
                </td>

                <td class="px-6 py-4 text-center">
                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                 bg-{{ $sColors[$wo->status] }}-100
                                 text-{{ $sColors[$wo->status] }}-700">
                        {{ $sLabels[$wo->status] }}
                    </span>
                </td>

                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $wo->technician?->name ?? '—' }}
                </td>

                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.work-orders.chat', $wo) }}"
                       class="relative inline-flex items-center px-3 py-1.5 rounded-lg text-xs bg-indigo-100 hover:bg-indigo-200 text-indigo-700 transition">
                        <i class="fas fa-comments mr-1.5"></i>
                        <span>Chat</span>
                        @if($hasUnread)
                            <span class="absolute -top-1 -right-1 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-white text-[9px] font-bold items-center justify-center">
                                    {{ $wo->unread_count }}
                                </span>
                            </span>
                        @endif
                    </a>
                </td>

                <td class="px-6 py-4 text-center">
                    <a href="{{ route('admin.work-orders.show', $wo) }}"
                       class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs">
                        Detail
                    </a>
                </td>

            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
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

{{-- SCRIPT SEARCH --}}
<script>
document.getElementById("searchWO").addEventListener("keyup", function() {

    let keyword = this.value.toLowerCase();
    let rows = document.querySelectorAll(".wo-row");

    rows.forEach(function(row) {

        let text = row.innerText.toLowerCase();

        if(text.includes(keyword)){
            row.style.display = "";
        } else {
            row.style.display = "none";
        }

    });

});
</script>

@endsection