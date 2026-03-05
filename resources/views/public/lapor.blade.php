<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Kerusakan | E-WorkOrder</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col items-center">

{{-- Navbar --}}
<nav class="bg-white shadow-sm w-full">
    <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600">E-WorkOrder RS</h1>
        <a href="{{ route('login') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition">
            Login Admin / User
        </a>
    </div>
</nav>

{{-- Error Alert --}}
@if($errors->any())
<div class="max-w-xl w-full mt-6 px-4">
    <div class="bg-red-100 text-red-700 p-3 rounded-lg shadow">
        <ul class="list-disc pl-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

{{-- Form Laporan --}}
<div class="max-w-xl w-full mt-6 mb-10 bg-white p-6 rounded-xl shadow mx-4">
    <h2 class="text-xl font-bold mb-4 text-gray-700 border-b pb-2">
        Form Laporan Kerusakan
    </h2>

    <form action="{{ route('public.report.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="space-y-3">
            {{-- Nama Pelapor --}}
            <input type="text" name="name" placeholder="Nama Pelapor" value="{{ old('name') }}"
                   class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>

            {{-- Input WhatsApp (Pengganti Email) --}}
            <div class="relative">
                <input type="tel" name="whatsapp" id="whatsapp" placeholder="Nomor WhatsApp (Contoh: 08123456789)" 
                    value="{{ old('whatsapp') }}"
                    class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none pl-10" required>
                <div class="absolute left-3 top-2.5 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.82c1.516.903 3.124 1.379 4.761 1.38h.005c5.424 0 9.838-4.414 9.84-9.839.002-2.628-1.023-5.1-2.887-6.963-1.864-1.864-4.336-2.889-6.965-2.89-5.424 0-9.838 4.414-9.84 9.839-.001 1.738.457 3.432 1.326 4.907l-.991 3.62 3.703-.971zm11.367-7.4c-.301-.15-1.781-.879-2.056-.979-.275-.1-.475-.15-.675.15-.2.3-.775 1.05-.95 1.25-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.794-1.49-1.775-1.665-2.075-.175-.3-.019-.463.13-.612.135-.133.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.594-.512-.513-.675-.521-.175-.008-.375-.01-.575-.01s-.525.075-.8.375c-.275.3-1.05 1.025-1.05 2.5s1.075 2.9 1.225 3.1c.15.2 2.112 3.224 5.118 4.522.715.309 1.273.493 1.708.632.718.228 1.372.196 1.889.119.577-.087 1.781-.727 2.031-1.427.25-.7.25-1.3.175-1.425-.075-.125-.275-.2-.575-.35z"/>
                    </svg>
                </div>
            </div>

            {{-- Lokasi --}}
            <input type="text" name="location" placeholder="Lokasi / Unit" value="{{ old('location') }}"
                   class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>

            {{-- Nama Barang --}}
            <input type="text" name="item_name" placeholder="Nama Barang / Alat" value="{{ old('item_name') }}"
                   class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>

            {{-- Kategori --}}
            <select name="kategori" class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="jaringan" {{ old('kategori') == 'jaringan' ? 'selected' : '' }}>Jaringan</option>
                <option value="hardware" {{ old('kategori') == 'hardware' ? 'selected' : '' }}>Hardware</option>
                <option value="software" {{ old('kategori') == 'software' ? 'selected' : '' }}>Software</option>
                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
            </select>

            {{-- Deskripsi --}}
            <textarea name="description" placeholder="Deskripsi Kerusakan" rows="4" 
                      class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none" required>{{ old('description') }}</textarea>

            {{-- Input Foto --}}
            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                <label class="block text-sm font-semibold text-blue-700 mb-1">Upload Foto Kerusakan</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                <p class="text-[10px] text-blue-400 mt-1">* Anda dapat memilih lebih dari 1 foto</p>
            </div>

            {{-- Signature --}}
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-600 mb-2">Tanda Tangan Pelapor</label>
                <div class="border rounded-lg bg-gray-50 overflow-hidden">
                    <canvas id="signature-pad" class="w-full cursor-crosshair" style="height:200px;"></canvas>
                </div>
                <input type="hidden" name="tanda_tangan" id="tanda_tangan">
                <div class="flex justify-between mt-2">
                    <button type="button" id="clear-signature" class="text-xs bg-gray-400 text-white px-3 py-1 rounded hover:bg-gray-500 transition">
                        Hapus Tanda Tangan
                    </button>
                    <span class="text-[10px] text-gray-400">Gunakan mouse atau layar sentuh</span>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full mt-6 bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition font-bold shadow-lg">
            KIRIM LAPORAN SEKARANG
        </button>
    </form>
</div>

<footer class="mt-auto py-6 text-sm text-gray-400">
    © {{ date('Y') }} E-WorkOrder RS
</footer>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Inisialisasi Signature Pad
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Fungsi resize canvas agar responsive
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = 200 * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear(); // Bersihkan jika resize
    }

    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    // Logika saat form dikirim
    document.querySelector("form").addEventListener("submit", function (e) {
        if (signaturePad.isEmpty()) {
            e.preventDefault();
            Swal.fire('Perhatian', 'Silakan isi tanda tangan terlebih dahulu', 'warning');
            return;
        }
        // Masukkan data base64 tanda tangan ke input hidden
        document.getElementById('tanda_tangan').value = signaturePad.toDataURL("image/png");
    });

    // Tombol hapus tanda tangan
    document.getElementById("clear-signature").addEventListener("click", () => signaturePad.clear());
</script>

{{-- Tampilkan SweetAlert jika ada session success --}}
@if(session('success_data'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Laporan Terkirim!',
        html: `
            <div class="text-left text-sm space-y-2 p-2 bg-gray-50 rounded-lg border">
                <p><b>No WO:</b> <span class="text-blue-600 font-mono">{{ session('success_data.code') }}</span></p>
                <p><b>Nama:</b> {{ session('success_data.nama') }}</p>
                <p><b>Unit:</b> {{ session('success_data.location') }}</p>
                <hr class="my-2">
                <p class="text-center font-semibold text-green-600">
                    Detail laporan telah dikirim melalui WhatsApp ke nomor Anda.
                </p>
            </div>
        `,
        confirmButtonText: 'Selesai',
        confirmButtonColor: '#2563eb'
    }).then(() => {
        window.location.href = "{{ route('landing') }}"; // Arahkan kembali ke landing page
    });
</script>
@endif

</body>
</html>