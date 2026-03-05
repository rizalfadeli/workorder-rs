<?php

namespace App\Exports;

use App\Models\WorkOrder;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CompletedWorkOrdersExport implements 
    FromCollection, 
    WithHeadings, 
    WithMapping,
    WithStyles
{
    public function collection()
    {
        return WorkOrder::with('technician')
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Code',
            'Item Name',
            'lokasi (unit)',
            'deskripsi',
            'Nama Pelapor',
            'Priority',
            'Status',
            'teknisi',
            'di buat',
            'Udi update',
            'Kategori',
            'Durasi Penyelesaian (Jam)',
        ];
    }

    public function map($wo): array
    {
        $durasiJam = ($wo->created_at && $wo->updated_at)
            ? $wo->created_at->diffInHours($wo->updated_at)
            : 0;

        return [
            $wo->code,
            $wo->item_name,
            $wo->location,
            $wo->description,
            $wo->nama_pelapor,
            $wo->priority,
            $wo->status,
            $wo->technician->name ?? '-', // ✅ Pakai nama teknisi
            $wo->created_at ? $wo->created_at->format('d-m-Y H:i') : null,
            $wo->updated_at ? $wo->updated_at->format('d-m-Y H:i') : null,
            $wo->kategori,
            $durasiJam,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header Bold + Center
        $sheet->getStyle('A1:M1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Set lebar kolom manual supaya tidak kepanjangan
        $sheet->getColumnDimension('A')->setWidth(18); // Code
        $sheet->getColumnDimension('B')->setWidth(20); // Item
        $sheet->getColumnDimension('C')->setWidth(20); // Location
        $sheet->getColumnDimension('D')->setWidth(40); // Description (dibatasi)
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(18);
        $sheet->getColumnDimension('M')->setWidth(22);

        // ✅ Wrap text supaya deskripsi turun ke bawah
        $sheet->getStyle('D:D')->getAlignment()->setWrapText(true);

        // Biar semua row auto tinggi sesuai isi
        $sheet->getDefaultRowDimension()->setRowHeight(-1);

        return [];
    }
}