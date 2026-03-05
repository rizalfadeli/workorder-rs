<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Work Order') - SIMRS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @stack('styles')
    <style>
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

        @keyframes pulse-badge {
            0%, 100% { transform: scale(1); }
            50%       { transform: scale(1.2); }
        }
        .badge-pulse { animation: pulse-badge 1s ease-in-out infinite; }
    </style>
</head>
<body class="bg-gray-50 font-sans">

{{-- Toast Container --}}
<div id="toast-container"
     class="fixed top-5 right-5 z-50 space-y-3 pointer-events-none"
     style="min-width: 320px;"></div>

{{-- Navbar --}}
<nav class="bg-white shadow-sm border-b sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">

        <div class="flex items-center gap-3">
            <span class="text-blue-700 font-bold text-lg">🏥 SIMRS Work Order</span>
        </div>

        <div class="flex items-center gap-1">

            <a href="{{ route('user.dashboard') }}"
               class="px-4 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('user.dashboard')
                          ? 'bg-blue-50 text-blue-600 font-semibold'
                          : 'text-gray-600 hover:bg-gray-100' }}">
                Dashboard
            </a>

            {{-- Work Order + badge --}}
            <a href="{{ route('user.work-orders.index') }}"
               class="relative px-4 py-2 rounded-lg text-sm transition
                      {{ request()->routeIs('user.work-orders.*')
                          ? 'bg-blue-50 text-blue-600 font-semibold'
                          : 'text-gray-600 hover:bg-gray-100' }}">
                Work Order Saya
                <span id="nav-badge"
                      class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs
                             font-bold w-5 h-5 rounded-full items-center justify-center
                             badge-pulse">
                    0
                </span>
            </a>

            {{-- Bell notifikasi --}}
            <div class="relative mx-2">
                <button id="bell-btn"
                        class="w-9 h-9 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center
                               justify-center text-gray-600 transition relative">
                    <i class="fas fa-bell"></i>
                    <span id="bell-badge"
                          class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs
                                 font-bold w-5 h-5 rounded-full items-center justify-center
                                 badge-pulse">
                        0
                    </span>
                </button>
            </div>

            <div class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 border-l ml-2">
                <div class="w-7 h-7 bg-blue-100 text-blue-600 rounded-full flex items-center
                            justify-center font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="font-medium">{{ auth()->user()->name }}</span>
                @if(auth()->user()->unit)
                    <span class="text-gray-400 text-xs">· {{ auth()->user()->unit }}</span>
                @endif
            </div>

            <form action="{{ route('logout') }}" method="POST" class="ml-2">
                @csrf
                <button class="px-3 py-2 text-sm text-red-500 hover:text-red-700
                               hover:bg-red-50 rounded-lg transition">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>

        </div>
    </div>
</nav>

<main class="max-w-6xl mx-auto px-4 py-8">

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-green-100 border border-green-400
                    text-green-700 px-4 py-3 rounded-xl">
            <i class="fas fa-check-circle text-green-500"></i>
            <span>{{ session('success') }}</span>
        </div>
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

</main>

@stack('scripts')

{{-- ==================== NOTIFIKASI SCRIPT ==================== --}}
<script>
const unreadUrl = "{{ route('user.unread-count') }}";
let prevCount   = 0;
let isFirstLoad = true;

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

function updateBadge(count) {
    const navBadge  = document.getElementById('nav-badge');
    const bellBadge = document.getElementById('bell-badge');
    const label     = count > 99 ? '99+' : count;

    if (count > 0) {
        navBadge.textContent = label;
        navBadge.classList.remove('hidden');
        navBadge.classList.add('flex');

        bellBadge.textContent = label;
        bellBadge.classList.remove('hidden');
        bellBadge.classList.add('flex');
    } else {
        navBadge.classList.add('hidden');
        navBadge.classList.remove('flex');

        bellBadge.classList.add('hidden');
        bellBadge.classList.remove('flex');
    }
}

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
    } catch (e) {
        // Abaikan jika tidak support
    }
}

async function pollUnread() {
    try {
        const res   = await fetch(unreadUrl);
        const data  = await res.json();
        const count = data.count;

        updateBadge(count);

        if (!isFirstLoad && count > prevCount) {
            const newMsg = count - prevCount;
            showToast(
                `💬 ${newMsg} pesan baru dari Admin RS!`,
                'info'
            );
            playNotificationSound();
        }

        prevCount   = count;
        isFirstLoad = false;

    } catch (e) {
        console.error('Unread poll error:', e);
    }
}

pollUnread();
setInterval(pollUnread, 5000);
</script>

</body>
</html>