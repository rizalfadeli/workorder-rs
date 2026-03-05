@extends('layouts.user')
@section('title', 'Buat Work Order')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Buat Laporan Kerusakan</h2>
        <p class="text-gray-500 mt-1">
            Isi detail kerusakan dengan lengkap agar tim teknis dapat segera menangani.
        </p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-8">
        <form action="{{ route('user.work-orders.store') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6"
              id="woForm">
            @csrf

            {{-- ================= DATA PELAPOR ================= --}}
            {{-- ================= DATA PELAPOR ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Pelapor <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                    name="nama_pelapor" 
                    value="{{ old('nama_pelapor') }}" 
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                    name="whatsapp" 
                    value="{{ old('whatsapp') }}" 
                    required
                    placeholder="Contoh: 08123456789"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            {{-- ================= DETAIL BARANG ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Barang / Alat <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="item_name" 
                       value="{{ old('item_name') }}" 
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                @error('item_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Lokasi / Unit <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="location" 
                       value="{{ old('location') }}" 
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                @error('location')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= KATEGORI ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Kategori Kerusakan <span class="text-red-500">*</span>
                </label>
                <select name="kategori" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="hardware" {{ old('kategori')=='hardware'?'selected':'' }}>Hardware</option>
                    <option value="jaringan" {{ old('kategori')=='jaringan'?'selected':'' }}>Jaringan</option>
                    <option value="software" {{ old('kategori')=='software'?'selected':'' }}>Software</option>
                    <option value="Lainnya" {{ old('kategori')=='Lainnya'?'selected':'' }}>Lainnya</option>
                </select>

                @error('kategori')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= DESKRIPSI ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Deskripsi Kerusakan <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none resize-none">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ================= FOTO ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Foto Kerusakan (Opsional)
                </label>
                <input type="file" 
                       name="images[]" 
                       multiple 
                       accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2">
            </div>

            {{-- ================= TANDA TANGAN ================= --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanda Tangan Pelapor <span class="text-red-500">*</span>
                </label>

                <div class="border border-gray-300 rounded-lg p-3 bg-gray-50">
                    <canvas id="signature-pad" 
                            class="border rounded-lg bg-white w-full h-40"></canvas>
                </div>

                <input type="hidden" name="tanda_tangan" id="tanda_tangan">

                <div class="flex gap-3 mt-3">
                    <button type="button" onclick="clearSignature()" 
                            class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                        Hapus
                    </button>
                </div>
            </div>

            {{-- ================= BUTTON ================= --}}
            <div class="flex gap-3 pt-2">
                <a href="{{ route('user.work-orders.index') }}"
                   class="flex-1 text-center border border-gray-300 text-gray-700 py-3 rounded-lg font-medium hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition">
                    Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Signature --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
const canvas = document.getElementById("signature-pad");
const signaturePad = new SignaturePad(canvas);

function resizeCanvas() {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width * ratio;
    canvas.height = rect.height * ratio;
    canvas.getContext("2d").scale(ratio, ratio);
    signaturePad.clear();
}
window.addEventListener("resize", resizeCanvas);
resizeCanvas();

function clearSignature() {
    signaturePad.clear();
}

document.getElementById("woForm").addEventListener("submit", function(e) {
    if (signaturePad.isEmpty()) {
        Swal.fire({
            icon: 'warning',
            title: 'Tanda tangan wajib diisi!'
        });
        e.preventDefault();
        return;
    }
    document.getElementById("tanda_tangan").value =
        signaturePad.toDataURL("image/png");
});
</script>

@endsection