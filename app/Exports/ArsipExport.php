<?php

// app/Exports/ArsipExport.php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ArsipExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    protected $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    public function collection()
    {
        $arsip = $this->data['arsip'];
        $jenisArsip = $this->data['jenisArsip'];
        
        return $arsip->map(function ($item, $key) use ($jenisArsip) {
            $row = [
                $key + 1,
                $item->nomor ?? '-',
                $item->judul ?? $item->nama ?? '-',
                $item->created_at->format('d/m/Y'),
                $item->user->nama_lengkap,
            ];
            
            // Tambahkan kolom khusus berdasarkan jenis arsip
            if ($jenisArsip === 'aktif') {
                $row[] = $item->kurun_waktu ?? '-';
                $row[] = $item->tingkat_perkembangan ?? '-';
                $row[] = $item->jumlah ?? '-';
                $row[] = $item->lokasi_simpan ?? '-';
                $row[] = $item->deskripsi_fisik ?? '-';
                $row[] = $item->media_arsip ?? '-';
            } elseif ($jenisArsip === 'inaktif') {
                $row[] = $item->kurun_waktu ?? '-';
                $row[] = $item->tingkat_perkembangan ?? '-';
                $row[] = $item->jumlah ?? '-';
                $row[] = $item->lokasi_simpan ?? '-';
                $row[] = $item->deskripsi_fisik ?? '-';
                $row[] = $item->media_arsip ?? '-';
            } elseif ($jenisArsip === 'vital') {
                $row[] = $item->kurun_waktu ?? '-';
                $row[] = $item->tingkat_perkembangan ?? '-';
                $row[] = $item->jumlah ?? '-';
                $row[] = $item->lokasi_simpan ?? '-';
                $row[] = $item->deskripsi_fisik ?? '-';
                $row[] = $item->media_arsip ?? '-';
            } elseif ($jenisArsip === 'alihmedia') {
                $row[] = $item->media_asal ?? '-';
                $row[] = $item->media_tujuan ?? '-';
                $row[] = $item->jumlah ?? '-';
                $row[] = $item->lokasi_simpan ?? '-';
                $row[] = $item->deskripsi_fisik ?? '-';
            }
            
            $row[] = $item->status ?? '-';
            $row[] = $item->keterangan ?? '-';
            
            return $row;
        });
    }
    
    public function headings(): array
    {
        $jenisArsip = $this->data['jenisArsip'];
        
        $headings = [
            'No',
            'Nomor Arsip',
            'Judul/Nama',
            'Tanggal Dibuat',
            'Pembuat',
        ];
        
        // Tambahkan heading khusus berdasarkan jenis arsip
        if ($jenisArsip === 'aktif') {
            $headings = array_merge($headings, [
                'Kurun Waktu',
                'Tingkat Perkembangan',
                'Jumlah',
                'Lokasi Simpan',
                'Deskripsi Fisik',
                'Media Arsip',
            ]);
        } elseif ($jenisArsip === 'inaktif') {
            $headings = array_merge($headings, [
                'Kurun Waktu',
                'Tingkat Perkembangan',
                'Jumlah',
                'Lokasi Simpan',
                'Deskripsi Fisik',
                'Media Arsip',
            ]);
        } elseif ($jenisArsip === 'vital') {
            $headings = array_merge($headings, [
                'Kurun Waktu',
                'Tingkat Perkembangan',
                'Jumlah',
                'Lokasi Simpan',
                'Deskripsi Fisik',
                'Media Arsip',
            ]);
        } elseif ($jenisArsip === 'alihmedia') {
            $headings = array_merge($headings, [
                'Media Asal',
                'Media Tujuan',
                'Jumlah',
                'Lokasi Simpan',
                'Deskripsi Fisik',
            ]);
        }
        
        $headings = array_merge($headings, [
            'Status',
            'Keterangan',
        ]);
        
        return $headings;
    }
    
    public function title(): string
    {
        return 'Laporan Arsip ' . ucfirst($this->data['jenisArsip']);
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}