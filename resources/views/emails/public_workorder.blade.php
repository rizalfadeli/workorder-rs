<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>

<h2>Laporan Kerusakan Berhasil Dikirim</h2>

<p>Terima kasih, laporan Anda telah kami terima dengan detail berikut:</p>

<hr>

<p><strong>Kode Laporan:</strong> {{ $workOrder->code }}</p>
<p><strong>Nama Pelapor:</strong> {{ $workOrder->name }}</p>
<p><strong>Email:</strong> {{ $workOrder->email }}</p>
<p><strong>Lokasi / Unit:</strong> {{ $workOrder->location }}</p>
<p><strong>Nama Barang:</strong> {{ $workOrder->item_name }}</p>
<p><strong>Deskripsi:</strong> {{ $workOrder->description }}</p>
<p><strong>Status:</strong> {{ $workOrder->status }}</p>

<hr>

<p>Silakan simpan kode laporan untuk tracking.</p>

<p>Terima kasih,<br>
E-WorkOrder RS</p>

</body>
</html>