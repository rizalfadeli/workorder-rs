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
<div class="bg-white rounded-xl shadow-sm p-5">
    <h4 class="font-semibold text-gray-700 mb-3">WhatsApp Gateway</h4>

    <div class="flex flex-col items-center space-y-3">
        {{-- Area Tombol Dinamis --}}
        <div id="wa-action-container">
            <button onclick="showQR()" id="btn-connect-wa"
                class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                Hubungkan WhatsApp
            </button>
            <button onclick="disconnectWA()" id="btn-logout-wa"
                class="hidden bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                Logout WhatsApp
            </button>
        </div>

        {{-- Status Terhubung --}}
        <div id="wa-status-badge" class="hidden flex items-center gap-2 text-green-600 font-semibold text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            WhatsApp Terhubung
        </div>

        <div class="text-xs text-gray-500 text-center space-y-1 max-w-xs">
            <p class="font-semibold text-gray-600">Cara Menghubungkan WhatsApp</p>
            <p>1. Buka aplikasi <b>WhatsApp</b> pada HP Admin.</p>
            <p>2. Masuk ke menu <b>Perangkat Tertaut</b>.</p>
            <p>3. Pilih <b>Tautkan Perangkat</b> lalu scan QR di atas.</p>
        </div>
    </div>
    <div id="qrModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 w-80 text-center shadow-lg">
            <h3 class="font-semibold text-gray-700 mb-3">
                Koneksi WhatsApp
            </h3>

            <div id="qr-container">
                <img id="fonnte-qr" class="w-56 mx-auto border rounded-lg"
                    src="https://via.placeholder.com/200?text=Loading+QR">
            </div>

            <div id="status-container" class="hidden py-4">
                <div class="bg-green-100 text-green-700 p-3 rounded-lg flex flex-col items-center">
                    <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <p class="font-bold">WhatsApp Terhubung!</p>
                </div>
            </div>

            <p id="qr-message" class="text-sm mt-3 text-gray-600">
                Silakan scan QR menggunakan WhatsApp anda.
            </p>

            <button onclick="closeQR()" class="mt-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-1 rounded">
                Tutup
            </button>
        </div>
    </div>
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

<script>
    let qrInterval = null;

    // Cek status saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        loadQR(true); // Hanya cek status saat awal load
    });

    function showQR() {
        document.getElementById("qrModal").classList.remove("hidden");
        document.getElementById("qrModal").classList.add("flex");
        
        // Reset state modal
        document.getElementById("qr-container").classList.remove("hidden");
        document.getElementById("status-container").classList.add("hidden");
        document.getElementById("fonnte-qr").src = "https://via.placeholder.com/200?text=Loading+QR";
        document.getElementById("qr-message").innerText = "Menghubungkan ke Fonnte...";
        
        loadQR(false); // Ambil QR (satu kali saja)
        
        // Mulai polling STATUS saja setiap 5 detik (untuk deteksi hasil scan)
        if(qrInterval) clearInterval(qrInterval);
        qrInterval = setInterval(() => {
            loadQR(true); // Kirim parameter true agar backend HANYA cek status (hemat limit)
        }, 5000);
    }

    function closeQR() {
        document.getElementById("qrModal").classList.add("hidden");
        if(qrInterval) clearInterval(qrInterval);
    }

    function updateUI(isConnected) {
        const btnConnect = document.getElementById("btn-connect-wa");
        const btnLogout = document.getElementById("btn-logout-wa");
        const badgeStatus = document.getElementById("wa-status-badge");

        if (isConnected) {
            btnConnect.classList.add("hidden");
            btnLogout.classList.remove("hidden");
            badgeStatus.classList.remove("hidden");
        } else {
            btnConnect.classList.remove("hidden");
            btnLogout.classList.add("hidden");
            badgeStatus.classList.add("hidden");
        }
    }

    function loadQR(statusOnly = false) {
        const url = statusOnly ? '/admin/fonnte-qr?status_only=1' : '/admin/fonnte-qr';

        fetch(url)
            .then(res => res.json())
            .then(data => {
                const qrImg = document.getElementById("fonnte-qr");
                const qrMsg = document.getElementById("qr-message");
                const qrCont = document.getElementById("qr-container");
                const statCont = document.getElementById("status-container");

                // Jika terhubung (BERHASIL SCAN)
                if (data.device_status === 'connect' || data.message === 'Perangkat sudah terhubung.') {
                    qrCont.classList.add("hidden");
                    statCont.classList.remove("hidden");
                    qrMsg.innerHTML = '<b class="text-green-600 uppercase tracking-wider">Berhasil Terhubung!</b><br>Silakan tutup modal ini.';
                    updateUI(true);
                    
                    if(qrInterval) clearInterval(qrInterval);
                    return;
                }

                updateUI(false);

                // Tampilkan QR HANYA jika ini BUKAN statusOnly (panggilan pertama)
                if (!statusOnly) {
                    let qrCode = data.url || (data.data ? data.data.qr : null);

                    if (qrCode) {
                        qrCont.classList.remove("hidden");
                        statCont.classList.add("hidden");
                        qrImg.src = "data:image/png;base64," + qrCode;
                        qrMsg.innerText = "Silakan scan QR menggunakan WhatsApp anda.";
                    } else if(data.status === false) {
                        qrMsg.innerHTML = `<span class="text-red-500 font-medium">${data.message || 'Limit tercapai atau Fonnte sedang sibuk.'}</span>`;
                    }
                }
            })
            .catch(err => {
                console.error('Fetch Error:', err);
            });
    }

    function disconnectWA() {
        if (!confirm('Apakah Anda yakin ingin memutuskan koneksi WhatsApp?')) return;

        fetch('https://api.fonnte.com/disconnect', {
            method: 'POST',
            headers: {
                'Authorization': '{{ config('services.fonnte.token') }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            alert('WhatsApp Berhasil Diputus.');
            location.reload();
        })
        .catch(err => {
            console.error(err);
            alert('Gagal memutuskan koneksi.');
        });
    }
</script>
@endsection