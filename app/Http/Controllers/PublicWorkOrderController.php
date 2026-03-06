<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;

class PublicWorkOrderController extends Controller
{
    public function __construct(private WhatsAppService $whatsAppService)
    {
    }

    public function landing(Request $request)
    {
        $query = WorkOrder::query();
        $latestWorkOrders = $query->latest()->paginate(5);

        $total      = WorkOrder::count();
        $submitted  = WorkOrder::where('status', 'submitted')->count();
        $progress   = WorkOrder::where('status', 'in_progress')->count();
        $completed  = WorkOrder::where('status', 'completed')->count();

        return view('landing', compact('latestWorkOrders', 'total', 'submitted', 'progress', 'completed'));
    }

    public function trackingAjax(Request $request)
    {
        $request->validate(['code' => 'required']);

        // Cari data WO beserta relasi teknisi
        $wo = WorkOrder::with('technician')
            ->where('code', $request->code)
            ->first();

        if (!$wo) {
            return response()->json([
                'status' => false,
                'message' => 'Nomor Work Order tidak ditemukan.'
            ]);
        }

        // Mapping status ke label Indonesia
        $statusLabels = [
            'submitted'   => 'Menunggu Antrean',
            'in_progress' => 'Sedang Dikerjakan',
            'completed'   => 'Selesai / Clear'
        ];

        return response()->json([
            'status' => true,
            'data' => [
                'code'           => $wo->code,
                'location'       => $wo->location,
                'item_name'      => $wo->item_name,
                'description'    => $wo->description,
                'nama_pelapor'   => $wo->nama_pelapor,
                'status'         => $wo->status,
                'status_text'    => $statusLabels[$wo->status] ?? $wo->status,
                'priority'       => strtoupper($wo->priority ?? 'low'),
                'technician'     => $wo->technician->name ?? 'Belum Ditentukan',
                'estimated_days' => $wo->estimated_days ?? '-',
                'created_at'     => $wo->created_at->format('d M Y, H:i'),
                'updated_at'     => $wo->updated_at->format('d M Y, H:i'),
            ]
        ]);
    }

    public function create()
    {
        return view('public.lapor');
    }

    public function track()
    {
        return redirect()->to(route('landing') . '#tracking');
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name'         => 'required|string|max:255',
            'whatsapp'     => 'required|numeric',
            'email'        => 'nullable|email|max:255',
            'location'     => 'required|string',
            'item_name'    => 'required|string',
            'kategori'     => 'required|string',
            'description'  => 'required|string',
            'tanda_tangan' => 'required',
            'images.*'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // 2. Proses Tanda Tangan (Base64 to File)
            $signatureData = $request->tanda_tangan;
            $signatureName = 'sig_' . time() . '_' . Str::random(5) . '.png';
            $signaturePath = 'signatures/' . $signatureName;
            
            $imageArray = explode(',', $signatureData);
            $decodedSignature = base64_decode(end($imageArray));
            Storage::disk('public')->put($signaturePath, $decodedSignature);

            // 3. Simpan Data Work Order
            $workOrder = WorkOrder::create([
                'code'          => 'WO-' . strtoupper(Str::random(6)),
                'nama_pelapor'     => $request->name,
                'whatsapp'      => $request->whatsapp,
                'email'         => $request->email, // tambahkan ini
                'location'      => $request->location,
                'item_name'     => $request->item_name,
                'kategori'      => $request->kategori,
                'description'   => $request->description,
                'tanda_tangan'  => $signaturePath,
                'status'        => 'submitted',
                
                /** * SOLUSI USER_ID:
                 * Jika user login, pakai ID-nya. 
                 * Jika tidak (publik), isi NULL (Pastikan database sudah Allow NULL)
                 * Atau ganti null menjadi 1 jika ingin dipaksa ke Admin ID 1.
                 */
                'user_id'       => auth()->id() ?? null, 
            ]);

            // 4. Proses Upload Foto (Jika ada)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $path = $file->store('attachments', 'public');
                    Attachment::create([
                        'work_order_id' => $workOrder->id,
                        'file_path'     => $path
                    ]);
                }
            }

            DB::commit();

            // 5. Kirim Notifikasi WhatsApp
            $this->whatsAppService->sendWorkOrderReceived((string) $request->whatsapp, $workOrder);

            return back()->with('success_data', [
                'code'     => $workOrder->code,
                'nama'     => $request->name,
                'location' => $request->location
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Log error untuk debug jika diperlukan
            \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
        }
    }
}
