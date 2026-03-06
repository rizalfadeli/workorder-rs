<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateWorkOrderRequest;
use App\Models\Attachment;
use App\Models\StatusLog;
use App\Models\Technician;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\BeritaAcaraMail;
use App\Mail\TechnicianAssignedMail;
use App\Models\ChatMessage;
use App\Services\WhatsAppService;
use App\Services\WhatsAppTeknisi;

class WorkOrderController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * LIST SEMUA WORK ORDER
     */
    public function index(Request $request)
    {
        $query = WorkOrder::with(['user', 'technician', 'chat.messages'])
            ->orderByPriority()
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $workOrders = $query->paginate(15);

        $adminId = auth()->id();
        $workOrders->each(function ($wo) use ($adminId) {
            $wo->unread_count = $wo->unreadMessagesFor($adminId);
        });

        $stats = [
            'total'        => WorkOrder::count(),
            'submitted'    => WorkOrder::where('status', 'submitted')->count(),
            'in_progress'  => WorkOrder::where('status', 'in_progress')->count(),
            'completed'    => WorkOrder::where('status', 'completed')->count(),
            'broken_total' => WorkOrder::where('status', 'broken_total')->count(),
        ];

        return view('admin.work-orders.index', compact('workOrders', 'stats'));
    }

    /**
     * DETAIL WORK ORDER
     */
    public function show(WorkOrder $workOrder)
    {
        $workOrder->load(['user', 'technician', 'attachments', 'statusLogs.changedBy']);
        $technicians = Technician::active()->get();

        return view('admin.work-orders.show', compact('workOrder', 'technicians'));
    }

    /**
     * UPDATE PRIORITAS / TEKNISI / ESTIMASI
     */
   public function update(UpdateWorkOrderRequest $request, WorkOrder $workOrder, WhatsAppTeknisi $waTeknisi)
{
    $validated = $request->validated();
    $oldTechnicianId = $workOrder->technician_id;

    $workOrder->update($validated);

    // Jika teknisi diganti, kirim WA
    if (isset($validated['technician_id']) && $validated['technician_id'] != $oldTechnicianId) {
        $technician = Technician::find($validated['technician_id']);

        if ($technician && !empty($technician->phone)) {

            $phone = $technician->phone;

            // ubah 0 di depan menjadi 62
            if (substr($phone, 0, 1) === '0') {
                $phone = '62' . substr($phone, 1);
            }

            $waTeknisi->sendWorkOrderReceived($phone, $workOrder);
        }
    }

    return redirect()->route('admin.work-orders.show', $workOrder)
        ->with('success', 'Work order berhasil diperbarui.');
}

    /**
     * UPDATE STATUS + AUDIT LOG
     */
    public function updateStatus(Request $request, WorkOrder $workOrder)
{
    $request->validate([
        'status' => 'required',
        'note' => 'nullable|string'
    ]);

    // LOGIKA PENGHAPUSAN
    if ($request->status === 'delete') {
        // 1. Hapus file lampiran fisik dari storage jika ada
        if ($workOrder->attachments) {
            foreach($workOrder->attachments as $attachment) {
                Storage::disk('public')->delete($attachment->file_path);
            }
        }
        
        // 2. Hapus file Berita Acara jika sudah pernah digenerate
        if ($workOrder->berita_acara_file) {
            Storage::disk('public')->delete($workOrder->berita_acara_file);
        }

        // 3. Hapus data dari database
        $workOrder->delete(); 

        // Ganti 'admin.work-orders' dengan nama route index Anda yang tepat
        return redirect()->route('admin.work-orders.index') 
            ->with('success', 'Work Order berhasil dihapus karena diselesaikan sendiri oleh unit.');
    }

    // LOGIKA UPDATE STATUS BIASA
    $workOrder->statusLogs()->create([
        'old_status' => $workOrder->status,
        'new_status' => $request->status,
        'note' => $request->note,
        'changed_by' => auth()->id(),
    ]);

    $workOrder->update(['status' => $request->status]);

    return back()->with('success', 'Status berhasil diperbarui.');
}
    /**
     * GENERATE BERITA ACARA + TANDA TANGAN + EMAIL USER
     */
    public function generateBeritaAcara(Request $request, WorkOrder $workOrder)
    {
        // 1. Simpan Tanda Tangan jika Method POST (Dari Modal)
        if ($request->isMethod('post') && $request->has('signature_admin')) {
            try {
                $dataUri = $request->input('signature_admin');
                $encoded_image = explode(",", $dataUri)[1];
                $decoded_image = base64_decode($encoded_image);
                
                $sigFolder = 'signatures';
                if (!Storage::disk('public')->exists($sigFolder)) {
                    Storage::disk('public')->makeDirectory($sigFolder);
                }

                $sigName = $sigFolder . '/ttd_admin_' . $workOrder->code . '_' . time() . '.png';
                Storage::disk('public')->put($sigName, $decoded_image);
                
                // Pastikan kolom ttd_admin sudah ada di migration dan fillable di Model
                $workOrder->update([
                    'ttd_admin' => $sigName
                ]);

            } catch (\Exception $e) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Gagal simpan TTD: ' . $e->getMessage()], 500);
                }
            }
        }

        // 2. Persiapan Data & Render PDF
        set_time_limit(120); // Tambah waktu agar tidak timeout saat render & kirim WA
        $workOrder->loadMissing(['user', 'technician', 'images']);
        $tanggal = now()->format('d M Y');

        $pdf = Pdf::loadView('admin.work-orders.berita_acara_pdf', [
            'workOrder' => $workOrder,
            'tanggal'   => $tanggal
        ])->setPaper('a4')
        ->setOptions([
            'isRemoteEnabled' => true, 
            'dpi' => 96,
            'defaultFont' => 'sans-serif',
        ]);

        // 3. Simpan File PDF Berita Acara
        $baFolder = 'berita_acara';
        if (!Storage::disk('public')->exists($baFolder)) {
            Storage::disk('public')->makeDirectory($baFolder);
        }

        $fileName = $baFolder . '/BA_' . $workOrder->code . '.pdf';
        $pdfOutput = $pdf->output();
        Storage::disk('public')->put($fileName, $pdfOutput);

        // Update info PDF di Database
        $workOrder->update([
            'berita_acara_file' => $fileName,
            'berita_acara_generated_at' => now()
        ]);

        // 4. Kirim Berita Acara melalui WhatsApp ke Pelapor (Teks Saja)
        $waSent = false;
        if (!empty($workOrder->whatsapp)) {
            $waSent = $this->whatsappService->sendBeritaAcara($workOrder->whatsapp, $workOrder, $fileName);
        }

        // 5. KIRIM EMAIL (DENGAN LAMPIRAN PDF)
        $mailSent = false;
        $pelaporEmail = $workOrder->email ?? ($workOrder->user->email ?? null);

        if (!empty($pelaporEmail)) {
            try {
                Mail::to($pelaporEmail)->send(new BeritaAcaraMail($workOrder, $fileName));
                $mailSent = true;
            } catch (\Exception $e) {
                Log::error("Gagal kirim email Berita Acara: " . $e->getMessage());
            }
        }

        // 6. Response Handling
        if ($request->expectsJson()) {
            $msg = 'Berita Acara berhasil dibuat.';
            $msg .= $waSent ? ' WA terkirim.' : ' WA gagal (hanya teks).';
            $msg .= $mailSent ? ' Email PDF terkirim ke ' . $pelaporEmail : ' Email gagal/tidak ada.';

            return response()->json([
                'success' => true,
                'message' => $msg
            ]);
        }

        return redirect()->route('admin.work-orders.show', $workOrder)
            ->with('success', 'Berita Acara berhasil diperbarui.');
    }

    public function unreadCount()
{
    $adminId = auth()->id();

    $count = ChatMessage::where('sender_id', '!=', $adminId)
        ->where('is_read', false)
        ->count();

    return response()->json([
        'count' => $count
    ]);
}
}