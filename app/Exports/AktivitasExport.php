<?php

// app/Exports/AktivitasExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AktivitasExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $aktivitas = $this->data['aktivitas'];
        
        return $aktivitas->map(function ($item, $key) {
            return [
                $key + 1,
                $item->created_at->format('d/m/Y H:i'),
                $item->user->nama_lengkap,
                $item->description,
            ];
        });
    }
    
    public function headings(): array
    {
        return [
            'No',
            'Tanggal & Waktu',
            'User',
            'Deskripsi Aktivitas',
        ];
    }
    
    public function title(): string
    {
        return 'Laporan Aktivitas';
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}