<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SoalTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Return sample data
        return [
            [
                'Apa itu algoritma?',
                'Bahasa pemrograman',
                'Urutan langkah-langkah untuk menyelesaikan masalah',
                'Aplikasi komputer',
                'Website',
                'B',
                'Konsep Dasar'
            ],
            [
                'Apa kepanjangan dari HTML?',
                'Hyper Text Markup Language',
                'High Tech Modern Language',
                'Home Tool Markup Language',
                'Hyperlink and Text Markup Language',
                'A',
                'Web Development'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Pertanyaan *',
            'Pilihan A *',
            'Pilihan B *',
            'Pilihan C *',
            'Pilihan D *',
            'Kunci Jawaban * (A/B/C/D)',
            'Kategori (Opsional)'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '667eea']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ]
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 40,
            'C' => 40,
            'D' => 40,
            'E' => 40,
            'F' => 25,
            'G' => 25,
        ];
    }
}
