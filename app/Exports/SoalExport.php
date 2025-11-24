<?php

namespace App\Exports;

use App\Models\Soal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SoalExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $ujianId;

    public function __construct($ujianId)
    {
        $this->ujianId = $ujianId;
    }

    public function collection()
    {
        $soals = Soal::with('pilihanJawaban')
            ->where('ujian_id', $this->ujianId)
            ->get();

        return $soals->map(function ($soal) {
            $pilihan = $soal->pilihanJawaban->sortBy('id')->values();
            
            return [
                'pertanyaan' => $soal->pertanyaan,
                'pilihan_a' => $pilihan[0]->pilihan ?? '',
                'pilihan_b' => $pilihan[1]->pilihan ?? '',
                'pilihan_c' => $pilihan[2]->pilihan ?? '',
                'pilihan_d' => $pilihan[3]->pilihan ?? '',
                'kunci_jawaban' => $soal->kunci_jawaban,
                'kategori' => '',
            ];
        });
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
