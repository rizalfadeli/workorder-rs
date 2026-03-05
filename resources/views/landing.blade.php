<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-WorkOrder RS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .fade-up {
            opacity: 0;
            transform: translateY(40px);
            transition: all 0.8s ease;
        }
        .fade-up.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 scroll-smooth">

{{-- ================= NAVBAR ================= --}}
<nav class="bg-white shadow-sm fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-700">
            E-WORKORDER RS
        </h1>

        <div class="flex gap-4">
            <a href="{{ route('login') }}"
               class="text-sm border px-4 py-2 rounded-lg text-blue-600 border-blue-300 hover:bg-blue-50 transition">
                Login
            </a>
        </div>
    </div>
</nav>

<div class="pt-24"></div>

{{-- ================= HERO ================= --}}
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-24">
    <div class="max-w-6xl mx-auto px-6 text-center fade-up">

        <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
            Sistem Pelaporan Kerusakan Fasilitas
        </h2>

        <p class="text-lg text-blue-100 mb-8">
            Laporkan gangguan fasilitas dan pantau progres penanganannya secara realtime.
        </p>

        <div class="flex justify-center gap-4">
            <a href="{{ route('public.report.create') }}"
               class="bg-white text-blue-700 px-6 py-3 rounded-lg font-semibold shadow hover:scale-105 transition">
                Buat Laporan Sekarang
            </a>

            <a href="#tracking"
               class="border border-white px-6 py-3 rounded-lg hover:bg-white hover:text-blue-700 transition">
                Tracking WO
            </a>
        </div>

    </div>
</section>

{{-- ================= TRACKING ================= --}}
<section id="tracking" class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-6 text-center fade-up">

        <h3 class="text-3xl font-bold mb-4">
            🔎 Tracking Work Order
        </h3>

        <p class="text-gray-500 mb-8">
            Masukkan nomor Work Order untuk melihat detail lengkap laporan.
        </p>

        <div class="flex gap-3 justify-center">
            <input type="text"
                   id="trackingCode"
                   placeholder="Contoh: WO-ABC123"
                   class="border px-4 py-3 rounded-lg w-2/3 focus:ring-2 focus:ring-blue-500">

            <button onclick="trackWO()"
                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 hover:scale-105 transition">
                Cek Status
            </button>
        </div>

        <div id="trackingResult" class="mt-10 hidden"></div>

    </div>
</section>

{{-- ================= TUTORIAL ================= --}}
<section class="py-20 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6 text-center fade-up">

        <h3 class="text-3xl font-bold mb-4">
            📘 Cara Membuat Laporan
        </h3>

        <p class="text-gray-500 mb-12">
            Ikuti langkah berikut untuk melaporkan kerusakan fasilitas dengan benar.
        </p>

        <div class="grid md:grid-cols-4 gap-8 text-left">

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-3xl font-bold mb-4">1</div>
                <h4 class="font-semibold mb-2">Klik Buat Laporan</h4>
                <p class="text-sm text-gray-500">
                    Tekan tombol <b>Buat Laporan Sekarang</b> di halaman utama.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-3xl font-bold mb-4">2</div>
                <h4 class="font-semibold mb-2">Isi Data Lengkap</h4>
                <p class="text-sm text-gray-500">
                    Isi nama pelapor, lokasi, item rusak, dan deskripsi kerusakan secara jelas.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-3xl font-bold mb-4">3</div>
                <h4 class="font-semibold mb-2">Tanda Tangan Digital</h4>
                <p class="text-sm text-gray-500">
                    Berikan tanda tangan pada kolom yang tersedia sebagai validasi laporan.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                <div class="text-blue-600 text-3xl font-bold mb-4">4</div>
                <h4 class="font-semibold mb-2">Pantau Progres</h4>
                <p class="text-sm text-gray-500">
                    Gunakan fitur <b>Tracking WO</b> untuk melihat status penanganan.
                </p>
            </div>

        </div>

    </div>
</section>


{{-- ================= FOOTER ================= --}}
<footer class="bg-blue-800 text-white py-6 text-center text-sm">
    © {{ date('Y') }} E-WorkOrder RS | Sistem Informasi Manajemen Fasilitas
</footer>

{{-- ================= SCRIPT ================= --}}
<script>
function trackWO() {

    const code = document.getElementById('trackingCode').value;
    const resultDiv = document.getElementById('trackingResult');

    if (!code) return;

    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = `
        <div class="text-blue-600 animate-pulse text-center">
            Mengecek data Work Order...
        </div>
    `;

    fetch(`{{ route('tracking.ajax') }}?code=${code}`)
        .then(response => response.json())
        .then(response => {

            if (!response.status) {
                resultDiv.innerHTML = `
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg text-center">
                        ${response.message}
                    </div>
                `;
                return;
            }

            const wo = response.data;

            let statusColor = 'bg-yellow-100 text-yellow-700';
            let statusText = 'MENUNGGU';
            let progressWidth = '25%';

            if (wo.status === 'in_progress') {
                statusColor = 'bg-blue-100 text-blue-700';
                statusText = 'SEDANG DIPROSES';
                progressWidth = '65%';
            }

            if (wo.status === 'completed') {
                statusColor = 'bg-green-100 text-green-700';
                statusText = 'SELESAI';
                progressWidth = '100%';
            }

            resultDiv.innerHTML = `
                <div class="bg-gray-50 p-8 rounded-xl shadow text-left">

                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-xl font-bold text-blue-700">
                            Detail Work Order
                        </h4>
                        <span class="${statusColor} px-3 py-1 rounded-full text-xs font-semibold">
                            ${statusText}
                        </span>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <p><b>No WO:</b> ${wo.code}</p>
                        <p><b>Lokasi:</b> ${wo.location}</p>
                        <p><b>Item:</b> ${wo.item_name}</p>
                        <p><b>Nama Pelapor:</b> ${wo.nama_pelapor}</p>
                        <p><b>Email:</b> ${wo.email}</p>
                        <p><b>Tanggal Lapor:</b> ${wo.created_at}</p>
                        <p><b>Terakhir Update:</b> ${wo.updated_at}</p>
                        <p><b>Prioritas:</b> ${wo.priority ?? '-'}</p>
                        <p><b>Estimasi:</b> ${wo.estimated_days ? wo.estimated_days + ' Hari' : '-'}</p>
                        <p><b>Teknisi:</b> ${wo.technician ?? '-'}</p>
                    </div>

                    <div class="mt-6">
                        <p class="font-semibold mb-2">Deskripsi:</p>
                        <div class="bg-white p-4 rounded border text-sm">
                            ${wo.description}
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="font-semibold mb-2">Progress:</p>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full transition-all duration-700"
                                 style="width:${progressWidth}"></div>
                        </div>
                    </div>

                </div>
            `;
        })
        .catch(() => {
            resultDiv.innerHTML = `
                <div class="bg-red-50 text-red-600 p-4 rounded-lg text-center">
                    Terjadi kesalahan sistem.
                </div>
            `;
        });
}

const faders = document.querySelectorAll('.fade-up');

const appearOnScroll = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('show');
        observer.unobserve(entry.target);
    });
}, { threshold: 0.2 });

faders.forEach(fader => {
    appearOnScroll.observe(fader);
});
</script>

</body>
</html>