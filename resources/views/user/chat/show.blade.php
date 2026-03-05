@extends('layouts.user')
@section('title', 'Chat Work Order')

@push('styles')
<style>
    #chat-wrapper {
        height: calc(100vh - 140px);
    }
</style>
@endpush

@section('content')
<div id="chat-wrapper" class="flex flex-col bg-white rounded-2xl shadow-sm overflow-hidden">

    {{-- Header Chat --}}
    <div class="bg-indigo-600 text-white px-6 py-4 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('user.work-orders.show', $workOrder) }}"
               class="w-9 h-9 bg-indigo-500 hover:bg-indigo-400 rounded-full flex items-center
                      justify-center transition" title="Kembali">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="w-10 h-10 bg-indigo-400 rounded-full flex items-center justify-center font-bold text-lg">
                A
            </div>
            <div>
                <p class="font-semibold">Admin Rumah Sakit</p>
                <p class="text-xs text-indigo-200">Tim Teknis</p>
            </div>
        </div>
        <div class="text-right">
            <p class="font-mono text-sm font-semibold">{{ $workOrder->code }}</p>
            <p class="text-xs text-indigo-200">{{ $workOrder->item_name }}</p>
        </div>
    </div>

    {{-- Info Strip --}}
    <div class="bg-indigo-50 border-b border-indigo-100 px-6 py-2 flex items-center gap-4 text-xs text-indigo-700 flex-shrink-0">
        @php
            $pLabels = ['high'=>'🔴 Tinggi','medium'=>'🟡 Sedang','low'=>'🟢 Rendah'];
            $sLabels = ['submitted'=>'Diajukan','in_progress'=>'Diproses','completed'=>'Selesai','broken_total'=>'Rusak Total'];
            $sBadge  = ['submitted'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','broken_total'=>'bg-red-100 text-red-700'];
        @endphp
        <span>Prioritas: <strong>{{ $pLabels[$workOrder->priority] }}</strong></span>
        <span>•</span>
        <span class="px-2 py-0.5 rounded-full font-medium {{ $sBadge[$workOrder->status] }}">
            {{ $sLabels[$workOrder->status] }}
        </span>
        @if($workOrder->estimated_days)
            <span>•</span>
            <span>Estimasi: <strong>{{ $workOrder->estimated_days }} hari</strong></span>
        @endif
        @if($workOrder->technician)
            <span>•</span>
            <span>Teknisi: <strong>{{ $workOrder->technician->name }}</strong></span>
        @endif
    </div>

    {{-- Area Pesan --}}
    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
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
                       placeholder="Tulis pesan untuk admin..."
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
const chatUrl     = "{{ route('user.work-orders.chat.send', $workOrder) }}";
const messagesUrl = "{{ route('user.work-orders.chat.messages', $workOrder) }}";
const csrfToken   = document.querySelector('meta[name="csrf-token"]').content;

let lastId      = 0;
let isFirstLoad = true;

function renderMessage(msg) {
    const wrapper = document.createElement('div');
    wrapper.className = `flex ${msg.is_mine ? 'justify-end' : 'justify-start'}`;
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
            document.getElementById('chat-loading').innerHTML = `
                <div class="text-center text-gray-400">
                    <div class="text-5xl mb-3">💬</div>
                    <p class="font-medium">Belum ada pesan</p>
                    <p class="text-sm mt-1">Mulai percakapan dengan admin.</p>
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

function handleEnter(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }
}

// Auto resize textarea saat mengetik
document.getElementById('msg-input').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = Math.min(this.scrollHeight, 120) + 'px';
});

pollMessages();
setInterval(pollMessages, 5000);
</script>
@endpush