@extends('layouts.admin')
@section('title', 'Chat WO')
@section('page-title', 'Chat - ' . $workOrder->code)

@push('styles')
<style>
    /* Override main content agar chat bisa fullscreen */
    #chat-wrapper {
        height: calc(100vh - 130px);
    }
</style>
@endpush

@section('content')
<div id="chat-wrapper" class="flex flex-col bg-white rounded-2xl shadow-sm overflow-hidden">

    {{-- Header Chat --}}
    <div class="bg-indigo-600 text-white px-6 py-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 bg-indigo-400 rounded-full flex items-center justify-center font-bold">
                {{ strtoupper(substr($workOrder->user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold">{{ $workOrder->user->name }}</p>
                <p class="text-xs text-indigo-200">{{ $workOrder->user->unit ?? '-' }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-mono text-sm font-semibold">{{ $workOrder->code }}</p>
            <p class="text-xs text-indigo-200">{{ $workOrder->item_name }} — {{ $workOrder->location }}</p>
        </div>
    </div>

    {{-- Info Strip --}}
    <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-2 flex items-center gap-4 text-xs text-indigo-700 flex-shrink-0">
        @php
            $pLabels = ['high'=>'🔴 Tinggi','medium'=>'🟡 Sedang','low'=>'🟢 Rendah'];
            $sLabels = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
        @endphp
        <span>Prioritas: <strong>{{ $pLabels[$workOrder->priority] }}</strong></span>
        <span>•</span>
        <span>Status: <strong>{{ $sLabels[$workOrder->status] }}</strong></span>
        @if($workOrder->technician)
            <span>•</span>
            <span>Teknisi: <strong>{{ $workOrder->technician->name }}</strong></span>
        @endif
        <span>•</span>
        <a href="{{ route('admin.work-orders.show', $workOrder) }}"
           class="text-indigo-600 hover:underline font-semibold">Lihat Detail WO →</a>
    </div>

    {{-- Area Pesan --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
        {{-- Pesan dimuat via JavaScript --}}
        <div id="chat-loading" class="flex justify-center items-center h-full">
            <div class="text-center text-gray-400">
                <div class="text-4xl mb-2">💬</div>
                <p class="text-sm">Memuat pesan...</p>
            </div>
        </div>
    </div>

    {{-- Input Area --}}
    <div class="border-t bg-white px-6 py-4 flex-shrink-0">
        <form id="chat-form" class="flex gap-3 items-end">
            @csrf
            <div class="flex-1">
                <textarea id="msg-input" rows="1"
                       placeholder="Tulis pesan untuk pelapor..."
                       class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm
                              focus:ring-2 focus:ring-indigo-500 outline-none transition resize-none"
                       onkeydown="handleEnter(event)"></textarea>
            </div>
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3
                           rounded-xl text-sm font-medium transition flex items-center gap-2 flex-shrink-0">
                <i class="fas fa-paper-plane"></i>
                Kirim
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">Tekan Enter untuk kirim, Shift+Enter untuk baris baru.</p>
    </div>

</div>
@endsection

@push('scripts')
<script>
const chatUrl     = "{{ route('admin.work-orders.chat.send', $workOrder) }}";
const messagesUrl = "{{ route('admin.work-orders.chat.messages', $workOrder) }}";
const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;

let lastId       = 0;
let isFirstLoad  = true;

function renderMessage(msg) {
    const wrapper = document.createElement('div');
    wrapper.className = `flex ${msg.is_mine ? 'justify-end' : 'justify-start'} animate-fade-in`;
    wrapper.innerHTML = `
        <div class="max-w-lg">
            <p class="text-xs text-gray-400 mb-1 ${msg.is_mine ? 'text-right' : 'text-left'}">
                ${msg.sender_name} · ${msg.time}
            </p>
            <div class="px-5 py-3 rounded-2xl text-sm leading-relaxed whitespace-pre-wrap
                        ${msg.is_mine
                            ? 'bg-indigo-600 text-white rounded-tr-none shadow-md'
                            : 'bg-white border border-gray-200 text-gray-800 rounded-tl-none shadow-sm'}">
                ${msg.message}
            </div>
        </div>`;
    return wrapper;
}

async function pollMessages() {
    try {
        const res  = await fetch(`${messagesUrl}?last_id=${lastId}`);
        const data = await res.json();

        if (data.messages.length > 0) {
            const container = document.getElementById('chat-messages');

            // Hapus loading indicator saat pesan pertama datang
            if (isFirstLoad) {
                container.innerHTML = '';
                isFirstLoad = false;
            }

            data.messages.forEach(msg => {
                container.appendChild(renderMessage(msg));
                lastId = msg.id;
            });
            container.scrollTop = container.scrollHeight;
        } else if (isFirstLoad) {
            // Tidak ada pesan sama sekali
            document.getElementById('chat-loading').innerHTML = `
                <div class="text-center text-gray-400">
                    <div class="text-5xl mb-3"></div>
                    <p class="font-medium">Belum ada pesan</p>
                    <p class="text-sm mt-1">Mulai percakapan dengan pelapor.</p>
                </div>`;
            isFirstLoad = false;
        }
    } catch (e) {
        console.error('Polling error:', e);
    }
}

document.getElementById('chat-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const input = document.getElementById('msg-input');
    const msg   = input.value.trim();
    if (!msg) return;
    input.value = '';
    input.style.height = 'auto';

    await fetch(chatUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ message: msg }),
    });

    pollMessages();
});

// Enter kirim, Shift+Enter baris baru
function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
}

// Auto resize textarea
document.getElementById('msg-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

pollMessages();
setInterval(pollMessages, 5000);
</script>
@endpush