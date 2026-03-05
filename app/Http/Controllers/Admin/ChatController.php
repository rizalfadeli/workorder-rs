<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreChatMessageRequest;
use App\Models\ChatMessage;
use App\Models\WorkOrder;

class ChatController extends Controller
{
    public function show(WorkOrder $workOrder)
    {
        $chat = $workOrder->chat()->firstOrCreate();
        $messages = $chat->messages()->with('sender:id,name')->get();
        return view('admin.chat.show', compact('workOrder', 'chat', 'messages'));
    }

    public function sendMessage(StoreChatMessageRequest $request, WorkOrder $workOrder)
    {
        $chat = $workOrder->chat()->firstOrCreate();
        $chat->messages()->create([
            'sender_id' => auth()->id(),
            'message'   => $request->message,
        ]);

        return response()->json(['status' => 'ok']);
    }

    /**
     * Polling endpoint: return pesan baru setelah last_id
     * Frontend poll setiap 5 detik
     */
    public function getMessages(WorkOrder $workOrder)
    {
        $chat = $workOrder->chat()->firstOrCreate();
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
                'date'        => $m->created_at->diffForHumans(),
            ]);

        // Tandai pesan dari user sebagai sudah dibaca
        $chat->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['messages' => $messages]);
    }
}