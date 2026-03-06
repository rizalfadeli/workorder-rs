<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWorkOrderRequest;
use App\Models\Attachment;
use App\Models\WorkOrder;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class WorkOrderController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private WhatsAppService $whatsAppService)
    {
    }

    /**
     * =============================
     * LIST WORK ORDER USER
     * =============================
     */
    public function index()
    {
        $userId = auth()->id();

        $workOrders = WorkOrder::forUser($userId)
            ->with(['technician', 'chat.messages'])
            ->orderByPriority()
            ->orderByDesc('created_at')
            ->paginate(10);

        $workOrders->each(function ($wo) use ($userId) {
            $wo->unread_count = $wo->unreadMessagesFor($userId);
        });

        return view('user.work-orders.index', compact('workOrders'));
    }

    public function show(WorkOrder $workOrder)
    {
        return view('user.work-orders.show', compact('workOrder'));
    }

    /**
     * =============================
     * FORM CREATE
     * =============================
     */
    public function create()
    {
        return view('user.work-orders.create');
    }

    /**
     * =============================
     * STORE WORK ORDER
     * =============================
     */
    public function store(StoreWorkOrderRequest $request)
    {
        $workOrder = null;

        DB::transaction(function () use ($request, &$workOrder) {

            // ================= SIMPAN TANDA TANGAN =================
            $signaturePath = null;

            if ($request->filled('tanda_tangan')) {
                $image = str_replace('data:image/png;base64,', '', $request->tanda_tangan);
                $image = str_replace(' ', '+', $image);
                $signaturePath = 'tanda_tangan/' . uniqid() . '.png';

                Storage::disk('public')->put(
                    $signaturePath,
                    base64_decode($image)
                );
            }

            // ================= BUAT WORK ORDER =================
            $workOrder = WorkOrder::create([
                'code'          => WorkOrder::generateCode(),
                'user_id'       => auth()->id(),
                'nama_pelapor'  => $request->nama_pelapor,
                'whatsapp'      => $request->whatsapp,
                'item_name'     => $request->item_name,
                'location'      => $request->location,
                'kategori'      => $request->kategori,
                'description'   => $request->description,
                'tanda_tangan'  => $signaturePath,
                'priority'      => 'low',
                'status'        => 'submitted',
            ]);

            // ================= SIMPAN FOTO =================
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {

                    $path = $image->store(
                        "work-orders/{$workOrder->id}/images",
                        'public'
                    );

                    Attachment::create([
                        'work_order_id' => $workOrder->id,
                        'type'          => 'image',
                        'file_path'     => $path,
                        'original_name' => $image->getClientOriginalName(),
                        'file_size'     => $image->getSize(),
                        'uploaded_by'   => auth()->id(),
                    ]);
                }
            }

            // ================= BUAT CHAT ROOM =================
            $workOrder->chat()->create();
        });

        // ================= KIRIM NOTIFIKASI WHATSAPP =================
        if (!empty($workOrder->whatsapp)) {
            $this->whatsAppService->sendWorkOrderReceived((string) $workOrder->whatsapp, $workOrder);
        }

        // ================= RETURN KE SWEET ALERT =================
        return redirect()
            ->route('user.work-orders.index')
            ->with('success_data', [
                'code'        => $workOrder->code,
                'nama'        => $workOrder->nama_pelapor,
                'whatsapp'    => $workOrder->whatsapp,
                'location'    => $workOrder->location,
                'item'        => $workOrder->item_name,
                'kategori'    => $workOrder->kategori,
                'description' => $workOrder->description,
            ]);
    }

}
