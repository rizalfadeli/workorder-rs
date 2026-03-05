<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SIMRS Work Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
    <style>
        /* Animasi toast notifikasi */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0);    opacity: 1; }
            to   { transform: translateX(100%); opacity: 0; }
        }
        .toast-enter { animation: slideIn 0.3s ease forwards; }
        .toast-leave { animation: slideOut 0.3s ease forwards; }

        /* Pulse badge */
        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.2); }
        }
        .badge-pulse { animation: pulse-badge 1s ease-in-out infinite; }
    </style>
</head>
<script src="//unpkg.com/alpinejs" defer></script>
<script src="//unpkg.com/alpinejs" defer></script>
<body class="bg-gray-100 font-sans">

{{-- Toast Container --}}
<div id="toast-container"
     class="fixed top-5 right-5 z-50 space-y-3 pointer-events-none"
     style="min-width: 320px;"></div>

<div class="flex h-screen overflow-hidden">

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="w-64 bg-blue-900 text-white flex flex-col shadow-xl flex-shrink-0">

        {{-- Logo --}}
        <div class="p-5 border-b border-blue-700">
            <h1 class="text-xl font-bold">SIMRS</h1>
            <p class="text-xs text-blue-300 mt-1">Work Order System</p>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                <span>Dashboard</span>
            </a>

            {{-- Work Orders --}}
            <a href="{{ route('admin.work-orders.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition
                      {{ request()->routeIs('admin.work-orders.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-clipboard-list w-5 text-center"></i>
                <span class="flex-1">Work Orders</span>
                {{-- Badge unread chat --}}
                <span id="sidebar-badge"
                      class="hidden bg-red-500 text-white text-xs font-bold px-2 py-0.5
                             rounded-full badge-pulse min-w-[20px] text-center">
                    0
                </span>
            </a>

            {{-- Teknisi --}}
            <a href="{{ route('admin.technicians.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition
                      {{ request()->routeIs('admin.technicians.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-user-gear w-5 text-center"></i>
                <span>Teknisi</span>
            </a>

            {{-- Pengguna --}}
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition
                      {{ request()->routeIs('admin.users.*') ? 'bg-blue-700' : '' }}">
                <i class="fas fa-users w-5 text-center"></i>
                <span>Pengguna</span>
            </a>

            <div class="border-t border-blue-700 my-2"></div>
            <p class="text-xs text-blue-400 uppercase px-4 py-1 tracking-wider">Filter WO</p>

            <a href="{{ route('admin.work-orders.index', ['status' => 'submitted']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition text-blue-200 hover:text-white">
                <i class="fas fa-inbox w-5 text-center text-yellow-400"></i>
                <span class="text-sm">WO Diajukan</span>
            </a>

            <a href="{{ route('admin.work-orders.index', ['status' => 'in_progress']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition text-blue-200 hover:text-white">
                <i class="fas fa-spinner w-5 text-center text-blue-400"></i>
                <span class="text-sm">WO Diproses</span>
            </a>

            <a href="{{ route('admin.work-orders.index', ['status' => 'completed']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition text-blue-200 hover:text-white">
                <i class="fas fa-check-circle w-5 text-center text-green-400"></i>
                <span class="text-sm">WO Selesai</span>
            </a>

            <a href="{{ route('admin.work-orders.index', ['status' => 'broken_total']) }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-blue-700 transition text-blue-200 hover:text-white">
                <i class="fas fa-times-circle w-5 text-center text-red-400"></i>
                <span class="text-sm">WO Rusak Total</span>
            </a>

        </nav>

        {{-- Profile & Logout --}}
        <div class="p-4 border-t border-blue-700 flex-shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center
                            font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-300">Admin RS</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-2 text-xs text-red-300
                               hover:text-red-100 hover:bg-blue-800 px-3 py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-1 overflow-y-auto flex flex-col min-w-0">

        {{-- Header --}}
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between flex-shrink-0">
            <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
            <div class="flex items-center gap-4">
                {{-- Bell notifikasi di header --}}
                <div class="relative">
                    <button id="bell-btn"
                            class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center
                                   justify-center text-gray-600 transition relative">
                        <i class="fas fa-bell"></i>
                        <span id="bell-badge"
                              class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs
                                     font-bold w-5 h-5 rounded-full flex items-center justify-center
                                     badge-pulse">
                            0
                        </span>
                    </button>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
        </header>

        {{-- Konten --}}
        <div class="flex-1 p-8">

        {{-- ================= POPUP BERHASIL BUAT BERITA ACARA ================= --}}
            @if(session()->has('success_ba'))
            @php
                $ba = session('success_ba');
            @endphp

            <div id="baModal"
                class="fixed inset-0 flex items-center justify-center z-50 bg-black/60">

                <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-6 relative animate-[fadeIn_.3s_ease]">

                    <button onclick="closeBaModal()"
                        class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-lg">
                        ✕
                    </button>

                    <div class="text-center">
                        <div class="text-green-600 text-5xl mb-3">✅</div>

                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Berita Acara Berhasil Dibuat
                        </h3>

                        <div class="text-sm text-gray-600 space-y-1 mt-3">
                            <p><strong>No WO:</strong> {{ $ba['code'] ?? '-' }}</p>
                            <p><strong>Pelapor:</strong> {{ $ba['user'] ?? '-' }}</p>
                            <p><strong>Dibuat pada:</strong> {{ $ba['tanggal'] ?? '-' }}</p>
                        </div>

                        @if(!empty($ba['file']))
                        <a href="{{ asset('storage/' . $ba['file']) }}"
                        target="_blank"
                        class="mt-5 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Lihat Berita Acara
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <script>
            function closeBaModal() {
                const modal = document.getElementById('baModal');
                if(modal){
                    modal.style.opacity = '0';
                    setTimeout(() => modal.remove(), 200);
                }
            }

            // AUTO CLOSE 4 DETIK
            setTimeout(() => {
                closeBaModal();
            }, 4000);
            </script>

            <style>
            @keyframes fadeIn {
                from { opacity:0; transform: scale(0.95); }
                to   { opacity:1; transform: scale(1); }
            }
            </style>

            @endif

            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 bg-red-100 border border-red-400
                            text-red-700 px-4 py-3 rounded-xl">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl">
                    <p class="font-medium mb-1">
                        <i class="fas fa-exclamation-triangle"></i> Terdapat kesalahan:
                    </p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </div>

    </main>
</div>

@stack('scripts')

{{-- ==================== NOTIFIKASI SCRIPT ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const unreadUrl = "{{ route('admin.unread-count') }}";

let prevCount = 0;
let isFirstLoad = true;
let lastAlertCount = 0;

/* =========================
   UPDATE BADGE
========================= */
function updateBadge(count) {
    const sidebarBadge = document.getElementById('sidebar-badge');
    const bellBadge    = document.getElementById('bell-badge');

    if (count > 0) {
        const label = count > 99 ? '99+' : count;

        sidebarBadge.textContent = label;
        sidebarBadge.classList.remove('hidden');

        bellBadge.textContent = label;
        bellBadge.classList.remove('hidden');
        bellBadge.classList.add('flex');
    } else {
        sidebarBadge.classList.add('hidden');
        bellBadge.classList.add('hidden');
    }
}

/* =========================
   SOUND NOTIFICATION
========================= */
function playNotificationSound() {
    try {
        const ctx  = new (window.AudioContext || window.webkitAudioContext)();
        const osc  = ctx.createOscillator();
        const gain = ctx.createGain();

        osc.connect(gain);
        gain.connect(ctx.destination);

        osc.frequency.setValueAtTime(880, ctx.currentTime);
        osc.frequency.setValueAtTime(660, ctx.currentTime + 0.1);
        gain.gain.setValueAtTime(0.3, ctx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);

        osc.start(ctx.currentTime);
        osc.stop(ctx.currentTime + 0.3);
    } catch (e) {}
}

/* =========================
   POLLING UNREAD
========================= */
function pollUnread() {
    fetch(unreadUrl)
        .then(res => res.json())
        .then(data => {

            const count = data.count;

            // Update badge
            updateBadge(count);

            /* =========================
               ALERT BESAR DOUBLE CHAT
            ========================= */
            if (!isFirstLoad && count >= 2 && count !== lastAlertCount) {

                Swal.fire({
                    title: 'ADA LAPORAN BELUM DITANGGAPI!!!',
                    html: `
                        <div style="font-size:18px;">
                            Ada <b>${count} pesan</b> belum dibaca!<br><br>
                            Segera cek periksa laporan
                        </div>
                    `,
                    icon: 'warning',
                    width: 600,
                    confirmButtonText: 'oke',
                    confirmButtonColor: 'rgb(55, 0, 255)'
                });

                playNotificationSound();
                lastAlertCount = count;
            }

            /* =========================
               TOAST PESAN BARU
            ========================= */
            if (!isFirstLoad && count > prevCount) {
                const newMsg = count - prevCount;

                showToast(
                    `${newMsg} pesan baru masuk dari pelapor!`,
                    'info'
                );

                playNotificationSound();
            }

            prevCount = count;
            isFirstLoad = false;

        })
        .catch(error => console.error('Unread poll error:', error));
}

/* =========================
   TOAST FUNCTION
========================= */
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast     = document.createElement('div');

    const colors = {
        info    : 'bg-indigo-600',
        success : 'bg-green-600',
        warning : 'bg-yellow-500',
    };

    toast.className = `pointer-events-auto flex items-center gap-3 px-4 py-3 rounded-xl
                       shadow-lg text-white text-sm font-medium toast-enter
                       ${colors[type] ?? colors.info}`;

    toast.innerHTML = `
        <i class="fas fa-comment-dots text-lg flex-shrink-0"></i>
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()"
                class="text-white/70 hover:text-white ml-2 flex-shrink-0">
            <i class="fas fa-times"></i>
        </button>`;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('toast-enter');
        toast.classList.add('toast-leave');
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

/* =========================
   START POLLING
========================= */
pollUnread();
setInterval(pollUnread, 5000);

</script>

</body>
</html>