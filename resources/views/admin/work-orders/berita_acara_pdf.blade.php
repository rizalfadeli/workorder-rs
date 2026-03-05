<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
        }

        .center { text-align: center; }
        .bold { font-weight: bold; }

        table { 
            width: 100%; 
            border-collapse: collapse; 
        }

        td { 
            vertical-align: top; 
            padding: 3px; 
        }

        .kop { 
            text-align: center; 
            line-height: 1.3; 
        }

        .title { 
            text-align: center; 
            font-weight: bold; 
            margin-top: 15px; 
            text-decoration: underline; 
        }

        .section {
            margin-top: 15px;
        }

        .ttd {
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .ttd img {
            height: 65px;
        }

        table, tr, td {
            page-break-inside: avoid !important;
        }

    </style>
</head>
<body>

{{-- ================= KOP SURAT ================= --}}
<div class="kop">
    <div class="bold">PEMERINTAH KABUPATEN NGANJUK</div>
    <div class="bold">DINAS KESEHATAN</div>
    <div class="bold">RUMAH SAKIT UMUM DAERAH NGANJUK</div>
    <div>Jalan Dr. Soetomo Nomor 62 Nganjuk Kode Pos 64415</div>
    <div>Tel. (0358) 321818, 326474, 326652, 328429</div>
    <div>Email : infoyan@rsud.nganjukkab.go.id</div>
</div>

<hr>

{{-- ================= JUDUL ================= --}}
<div class="title">
    BERITA ACARA PEMERIKSAAN PERANGKAT HARDWARE / SOFTWARE
</div>

<p class="center">
    Nomor : {{ $workOrder->code }}/BA/{{ date('Y') }}
</p>

<p>
Pada hari ini tanggal {{ $tanggal }}, kami yang bertandatangan di bawah ini:
</p>

<table>
    <tr>
        <td width="25%">Nama</td>
        <td width="75%">: {{ auth()->user()->name ?? 'Administrator' }}</td>
    </tr>
    <tr>
        <td>Jabatan</td>
        <td>: Administrator</td>
    </tr>
    <tr>
        <td>Bertindak untuk</td>
        <td>: RSUD Nganjuk</td>
    </tr>
</table>

<div class="section">
Telah melakukan pemeriksaan terhadap:
</div>

<table>
    <tr>
        <td width="30%">Jenis Barang / Aset</td>
        <td width="70%">: {{ $workOrder->item_name }}</td>
    </tr>
    <tr>
        <td>Lokasi Barang</td>
        <td>: {{ $workOrder->location }}</td>
    </tr>
    <tr>
        <td>Pelapor</td>
        <td>: {{ $workOrder->nama_pelapor ?? '-' }}</td>
    </tr>
    <tr>
        <td>Email Pelapor</td>
        <td>: {{ $workOrder->email ?? '-' }}</td>
    </tr>
    <tr>
        <td>Teknisi</td>
        <td>: {{ $workOrder->technician->name ?? '-' }}</td>
    </tr>
</table>

<div class="section">
<b>1. Kronologi / Riwayat Kerusakan :</b><br>
{{ $workOrder->description }}
</div>

<div class="section">
<b>2. Tindakan yang telah dilakukan :</b><br>
{{ $workOrder->admin_notes ?? '-' }}
<br><br>

Status Akhir : <b>{{ $workOrder->status_label }}</b>
</div>

<div class="section">
<b>Kesimpulan dan Saran :</b><br>
Berdasarkan pemeriksaan di atas, unit dengan kode {{ $workOrder->code }} dinyatakan:
<br><br>

@if($workOrder->status == 'completed')
✔ Dapat diperbaiki dan telah selesai diperbaiki.
@elseif($workOrder->status == 'broken_total')
✔ Tidak dapat untuk diperbaiki dan disarankan penghapusan aset.
@else
✔ Masih dalam proses penanganan lebih lanjut.
@endif
</div>

<p class="section">
Demikian Berita Acara ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
</p>

{{-- ================= TANDA TANGAN ================= --}}
<div class="ttd">
    <table>
        <tr>
            <td class="center" width="50%">
                Nganjuk, {{ $tanggal }}<br>
                <b>Pelapor</b><br><br>

                @if($workOrder->tanda_tangan)
                    @php
                        $path = storage_path('app/public/'.$workOrder->tanda_tangan);
                    @endphp

                    @if(file_exists($path))
                        @php
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        @endphp

                        <img src="{{ $base64 }}" height="65"><br>
                    @else
                        <div style="height:65px;"></div>
                    @endif
                @else
                    <div style="height:65px;"></div>
                @endif

                <b>{{ $workOrder->nama_pelapor ?? '-' }}</b>
            </td>

            <td class="center" width="50%">
                <b>Ka ISIK</b><br><br>

                @if($workOrder->ttd_admin)
                    @php
                        $pathAdmin = storage_path('app/public/' . $workOrder->ttd_admin);
                    @endphp

                    @if(file_exists($pathAdmin))
                        @php
                            $typeAdmin = pathinfo($pathAdmin, PATHINFO_EXTENSION);
                            $dataAdmin = file_get_contents($pathAdmin);
                            $base64Admin = 'data:image/' . $typeAdmin . ';base64,' . base64_encode($dataAdmin);
                        @endphp
                        <img src="{{ $base64Admin }}" height="65"><br>
                    @else
                        <div style="height:65px;"></div>
                    @endif
                @else
                    <div style="height:65px;"></div>
                @endif

                <br>
                <b>Nama Kepala ISIK</b><br>
                NIP. 123456789
            </td>
        </tr>
    </table>
</div>

</body>
</html>