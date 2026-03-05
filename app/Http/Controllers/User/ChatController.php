<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatMessageRequest;
use App\Models\WorkOrder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ChatController extends Controller
{
    use AuthorizesRequests;

    public function show(WorkOrder $workOrder)
    {
        if ($workOrder->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $chat = $workOrder->chat()->firstOrCreate();

        // Tandai semua pesan dari admin sebagai sudah dibaca
        // saat user membuka halaman chat
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $chat->messages()->with('sender:id,name')->get();

        return view('user.chat.show', compact('workOrder', 'chat', 'messages'));
    }

    public function sendMessage(StoreChatMessageRequest $request, WorkOrder $workOrder)
    {
        if ($workOrder->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $chat = $workOrder->chat()->firstOrCreate();
        $chat->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $request->message,
        ]);

        return response()->json(['status' => 'ok']);
    }

    public function getMessages(WorkOrder $workOrder)
    {
        if ($workOrder->user_id !== auth()->id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $chat   = $workOrder->chat()->firstOrCreate();
        $lastId = request()->integer('last_id', 0);

        $messages = $chat->messages()
            ->with('sender:id,name')
            ->where('id', '>', $lastId)
            ->get()
            ->map(fn ($m) => [
                'id'          => $m->id,
                'sender_name' => $m->sender->name,
                'is_mine'     => $m->sender_id === auth()->id(),
                'message'     => $m->message,
                'time'        => $m->created_at->format('H:i'),
            ]);

        // Tandai pesan baru dari admin sebagai sudah dibaca
        // saat polling berjalan (user sedang di halaman chat)
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }
}