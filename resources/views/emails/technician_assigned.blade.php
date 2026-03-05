<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: Arial, sans-serif; font-size:14px; color:#333;">

    <h3>Anda Mendapat Penugasan Work Order</h3>

    <p>Halo,</p>

    <p>Anda ditugaskan untuk menangani work order berikut:</p>

    <table cellpadding="6">
        <tr>
            <td><b>Kode WO</b></td>
            <td>: {{ $workOrder->code }}</td>
        </tr>
        <tr>
            <td><b>Barang</b></td>
            <td>: {{ $workOrder->item_name }}</td>
        </tr>
        <tr>
            <td><b>Lokasi</b></td>
            <td>: {{ $workOrder->location }}</td>
        </tr>
        <tr>
            <td><b>Pelapor</b></td>
            <td>: {{ $workOrder->nama_pelapor }}</td>
        </tr>
        <tr>
            <td><b>Deskripsi</b></td>
            <td>: {{ $workOrder->description }}</td>
        </tr>
    </table>

    <p style="margin-top:20px;">
        Silakan login ke sistem untuk mulai menangani pekerjaan.
    </p>

    <hr>
    <small>E-WorkOrder RSUD</small>

</body>
</html>