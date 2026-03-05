@extends('layouts.admin')
@section('title', 'Detail WO')
@section('page-title', 'Detail Work Order: ' . $workOrder->code)

@section('content')

<div class="grid grid-cols-3 gap-6">

    {{-- KOLOM KIRI: Info WO --}}
    <div class="col-span-2 space-y-6">
        

        {{-- Info Utama --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $workOrder->item_name }}</h3>
                    <p class="text-gray-500 font-mono text-sm">{{ $workOrder->code }}</p>
                </div>
                <div class="flex gap-2">
                    @php
                        $pBadge = ['high'=>'bg-red-100 text-red-700','medium'=>'bg-yellow-100 text-yellow-700','low'=>'bg-green-100 text-green-700'];
                        $sBadge = ['submitted'=>'bg-yellow-100 text-yellow-700','in_progress'=>'bg-blue-100 text-blue-700','completed'=>'bg-green-100 text-green-700','broken_total'=>'bg-red-100 text-red-700'];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $pBadge[$workOrder->priority] ?? 'bg-gray-100' }}">
                        {{ $workOrder->priority_label }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $sBadge[$workOrder->status] ?? 'bg-gray-100' }}">
                        {{ $workOrder->status_label }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                <div>
                    <p class="text-gray-400">Pelapor</p>
                    <p class="font-medium">{{ $workOrder->user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Unit / Lokasi</p>
                    <p class="font-medium">{{ $workOrder->location }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Tanggal Lapor</p>
                    <p class="font-medium">{{ $workOrder->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Estimasi</p>
                    <p class="font-medium">{{ $workOrder->estimated_days ? $workOrder->estimated_days . ' hari' : '-' }}</p>
                </div>
            </div>

            <div class="border-t pt-4">
                <p class="text-gray-400 text-sm mb-1">Deskripsi Kerusakan</p>
                <p class="text-gray-700">{{ $workOrder->description }}</p>
            </div>
        </div>

        {{-- Foto Kerusakan --}}
        @if($workOrder->images->count())
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-semibold text-gray-700 mb-4">📷 Foto Kerusakan</h4>
            <div class="grid grid-cols-3 gap-3">
                @foreach($workOrder->images as $img)
                <a href="{{ Storage::url($img->file_path) }}" target="_blank">
                    <img src="{{ Storage::url($img->file_path) }}"
                         class="w-full h-32 object-cover rounded-lg hover:opacity-80 transition cursor-pointer border">
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Log Status --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h4 class="font-semibold text-gray-700 mb-4">Riwayat Status</h4>
            <div class="space-y-3">
                @foreach($workOrder->statusLogs->sortByDesc('created_at') as $log)
                <div class="flex items-start gap-3 text-sm">
                    <div class="w-2 h-2 rounded-full bg-blue-400 mt-1.5 flex-shrink-0"></div>
                    <div>
                        <p class="text-gray-600">
                            <span class="font-medium">{{ $log->changedBy->name }}</span> mengubah status dari
                            <span class="text-gray-400">{{ $log->old_status ?? 'Baru' }}</span> →
                            <span class="font-semibold text-blue-600">{{ $log->new_status }}</span>
                        </p>
                        @if($log->note)
                            <p class="text-gray-400 text-xs mt-0.5">{{ $log->note }}</p>
                        @endif
                        <p class="text-gray-300 text-xs">{{ $log->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Action Panel --}}
<div class="space-y-4">

    @if($workOrder->status !== 'completed')

        {{-- Update Status --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-3">🔄 Update Status</h4>
            <form action="{{ route('admin.work-orders.update-status', $workOrder) }}" method="POST">
                @csrf @method('PATCH')
                {{-- Ganti bagian select status di kolom kanan --}}
                <select id="statusSelect" name="status" class="w-full border rounded-lg px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-500">
                    <option value="submitted"    {{ $workOrder->status === 'submitted'    ? 'selected' : '' }}>Diajukan</option>
                    <option value="in_progress"  {{ $workOrder->status === 'in_progress'  ? 'selected' : '' }}>Diproses</option>
                    <option value="completed"    {{ $workOrder->status === 'completed'    ? 'selected' : '' }}>Selesai</option>
                    <option value="broken_total" {{ $workOrder->status === 'broken_total' ? 'selected' : '' }}>Rusak Total</option>
                    {{-- Pastikan value-nya adalah 'delete' --}}
                    <option value="delete">Selesai (dikerjakan sendiri oleh unit)</option>
                </select>
                <textarea name="note" placeholder="Catatan (opsional)" rows="2"
                    class="w-full border rounded-lg px-3 py-2 text-sm mb-3 focus:ring-2 focus:ring-blue-500"></textarea>
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg text-sm font-medium transition">
                    Simpan Status
                </button>
            </form>
        </div>

        {{-- Update Prioritas & Teknisi --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h4 class="font-semibold text-gray-700 mb-3">⚙️ Pengaturan</h4>
            <form action="{{ route('admin.work-orders.update', $workOrder) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500">Prioritas</label>
                        <select name="priority" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="high"   {{ $workOrder->priority === 'high'   ? 'selected' : '' }}>🔴 Tinggi (2-7 hari)</option>
                            <option value="medium" {{ $workOrder->priority === 'medium' ? 'selected' : '' }}>🟡 Sedang (60 menit)</option>
                            <option value="low"    {{ $workOrder->priority === 'low'    ? 'selected' : '' }}>🟢 ringan (30 menit)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Teknisi</label>
                        <select name="technician_id" class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Belum ditentukan --</option>
                            @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $workOrder->technician_id === $tech->id ? 'selected' : '' }}>
                                {{ $tech->name }} ({{ $tech->specialty }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500">Catatan Admin</label>
                        <textarea name="admin_notes" rows="2"
                                  class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">{{ $workOrder->admin_notes }}</textarea>
                    </div>
                    <button class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

    @else

        {{-- Info jika sudah selesai --}}
        <div class="bg-green-50 border border-green-200 rounded-xl p-5 text-sm text-green-700">
            ✅ Work Order sudah selesai.  
            Status dan teknisi tidak dapat diubah lagi.
        </div>

    @endif

    {{-- Upload PDF Berita Acara (TETAP ADA) --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <h4 class="font-semibold text-gray-700 mb-3">Berita Acara</h4>

        @if(!empty($workOrder->berita_acara_file))

            <div class="mb-3 text-sm text-green-600 font-medium">
                ✅ Berita Acara sudah dibuat
                <br>
                <span class="text-gray-400 text-xs">
                    {{ \Carbon\Carbon::parse($workOrder->berita_acara_generated_at)->format('d M Y H:i') }}
                </span>
            </div>

            <a href="{{ asset('storage/' . $workOrder->berita_acara_file) }}"
            target="_blank"
            class="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition">
                Lihat Berita Acara
            </a>

            <button type="button" onclick="openSignatureModal()"
            class="w-full mt-2 flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 text-white py-2 rounded-lg text-sm font-medium transition">
                Generate Ulang
            </button>

        @else

            <button type="button" onclick="openSignatureModal()"
            class="w-full flex items-center justify-center gap-2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg text-sm font-medium transition">
                BUAT BERITA ACARA
            </button>

        @endif
    </div>

</div>


    

{{-- MODAL TANDA TANGAN KA ISIK --}}
<div id="signatureModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 py-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Tanda Tangan Ka ISIK</h3>
                    <button onclick="closeSignatureModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <p class="text-sm text-gray-500 mb-4 italic">
                    Silakan bubuhkan tanda tangan di bawah ini untuk keperluan Berita Acara.
                </p>

                <div class="relative border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 overflow-hidden">
                    <canvas id="signature-pad" class="w-full h-64 touch-none cursor-crosshair"></canvas>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="clearSignature()" 
                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium hover:bg-gray-200 transition">
                        Hapus
                    </button>
                    <button type="button" onclick="submitSignature()" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        Simpan & Generate
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<script>
    let signaturePad;
    const modal = document.getElementById('signatureModal');
    const canvas = document.getElementById('signature-pad');

    // Inisialisasi Signature Pad
    document.addEventListener('DOMContentLoaded', function() {
        signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0)',
            penColor: 'rgb(0, 0, 0)'
        });

        // Flash Messages
        @if(session('success'))
            Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success' });
        @endif

        @if(session('error'))
            Swal.fire({ title: 'Gagal!', text: "{{ session('error') }}", icon: 'error' });
        @endif
    });

    function openSignatureModal() {
        modal.classList.remove('hidden');
        resizeCanvas();
    }

    function closeSignatureModal() {
        modal.classList.add('hidden');
        signaturePad.clear();
    }

    function clearSignature() {
        signaturePad.clear();
    }

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }

    function submitSignature() {
        if (signaturePad.isEmpty()) {
            Swal.fire('Perhatian', 'Silakan masukkan tanda tangan terlebih dahulu.', 'warning');
            return;
        }

        const base64Data = signaturePad.toDataURL('image/png');

        Swal.fire({
            title: 'Memproses...',
            text: 'Sedang menyimpan tanda tangan dan membuat PDF',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading(); }
        });

        // Kirim data menggunakan Fetch API
        fetch("{{ route('admin.work-orders.generate-berita-acara', $workOrder) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                signature_admin: base64Data 
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', 'Berita acara telah digenerate.', 'success')
                .then(() => { window.location.reload(); });
            } else {
                throw new Error(data.message || 'Gagal memproses data');
            }
        })
        .catch(error => {
            Swal.fire('Error', error.message, 'error');
        });
    }

    // Menangani resize window agar canvas tetap pas
    window.onresize = resizeCanvas;
    // Tambahkan listener pada form update status
    document.querySelector('form[action*="update-status"]').addEventListener('submit', function(e) {
        const status = document.getElementById('statusSelect').value;
        
        if (status === 'delete') {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Laporan ini akan DIHAPUS permanen karena diselesaikan sendiri oleh unit.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus & Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        }
    });
</script>
@endsection