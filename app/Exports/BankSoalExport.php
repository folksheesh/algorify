<?php

namespace App\Exports;

use App\Models\BankSoal;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;

class BankSoalExport implements WithEvents, WithTitle
{
    protected $request;
    protected $soal;
    protected $countPilihanGanda;
    protected $countMultiJawaban;
    protected $countEssay;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->loadData();
    }

    protected function loadData()
    {
        $query = BankSoal::with(['kategori', 'kursus', 'creator']);

        if ($this->request->has('search') && $this->request->search != '') {
            $search = strtolower($this->request->search);
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(pertanyaan) like ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(tipe_soal) like ?', ["%{$search}%"]);
            });
        }

        if ($this->request->has('tipe_soal') && $this->request->tipe_soal != '') {
            $query->where('tipe_soal', $this->request->tipe_soal);
        }

        if ($this->request->has('kategori') && $this->request->kategori != '') {
            $query->where('kategori_id', $this->request->kategori);
        }

        if ($this->request->has('kursus') && $this->request->kursus != '') {
            $query->where('kursus_id', $this->request->kursus);
        }

        $this->soal = $query->latest()->get();
        $this->countPilihanGanda = $this->soal->where('tipe_soal', 'pilihan_ganda')->count();
        $this->countMultiJawaban = $this->soal->where('tipe_soal', 'multi_jawaban')->count();
        $this->countEssay = $this->soal->where('tipe_soal', 'essay')->count();
    }

    public function title(): string
    {
        return 'Bank Soal';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = 'I';
                
                // === HEADER SECTION ===
                // Row 1: Title
                $sheet->setCellValue('A1', 'LAPORAN BANK SOAL - ALGORIFY');
                $sheet->mergeCells('A1:I1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5D3FFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(35);

                // Row 2: Export Date
                $sheet->setCellValue('A2', 'Tanggal Export: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i:s'));
                $sheet->mergeCells('A2:I2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '64748B']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F1F5F9']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(22);

                // Row 3: Empty
                $sheet->getRowDimension(3)->setRowHeight(10);

                // Row 4: Summary Title
                $sheet->setCellValue('A4', 'RINGKASAN');
                $sheet->mergeCells('A4:B4');
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E293B']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E7FF']],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(25);

                // Summary data
                $summaryData = [
                    ['Total Soal', $this->soal->count()],
                    ['Pilihan Ganda', $this->countPilihanGanda],
                    ['Multi Jawaban', $this->countMultiJawaban],
                    ['Essay', $this->countEssay],
                ];
                
                $summaryRow = 5;
                foreach ($summaryData as $data) {
                    $sheet->setCellValue("A{$summaryRow}", $data[0]);
                    $sheet->setCellValue("B{$summaryRow}", $data[1]);
                    $sheet->getStyle("A{$summaryRow}")->getFont()->setBold(true);
                    $sheet->getStyle("B{$summaryRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    $summaryRow++;
                }

                // Apply border to summary section
                $sheet->getStyle('A4:B8')->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                    ],
                ]);

                // Row 9: Empty
                $sheet->getRowDimension(9)->setRowHeight(10);

                // Row 10: Detail Title
                $sheet->setCellValue('A10', 'DETAIL SOAL');
                $sheet->mergeCells('A10:I10');
                $sheet->getStyle('A10')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E293B']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E7FF']],
                ]);
                $sheet->getRowDimension(10)->setRowHeight(25);

                // Row 11: Column Headers
                $headers = ['No', 'Pertanyaan', 'Tipe Soal', 'Opsi Jawaban', 'Jawaban Benar', 'Kategori/Kursus', 'Poin', 'Dibuat Oleh', 'Tanggal'];
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue("{$col}11", $header);
                    $col++;
                }
                $sheet->getStyle('A11:I11')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5D3FFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(11)->setRowHeight(28);

                // Data rows starting from row 12
                $tipeSoalMap = [
                    'pilihan_ganda' => 'Pilihan Ganda',
                    'multi_jawaban' => 'Multi Jawaban',
                    'essay' => 'Essay',
                ];

                $dataRow = 12;
                foreach ($this->soal as $index => $item) {
                    $opsiStr = '';
                    $opsiJawaban = is_array($item->opsi_jawaban) ? $item->opsi_jawaban : [];
                    if (!empty($opsiJawaban)) {
                        $opsiStr = implode(' | ', $opsiJawaban);
                    }
                    
                    // Convert jawaban_benar index to actual answer text
                    $jawabanStr = '';
                    if ($item->tipe_soal === 'essay') {
                        $jawabanStr = $item->kunci_jawaban ?? '-';
                    } elseif (!empty($opsiJawaban)) {
                        if (is_array($item->jawaban_benar)) {
                            // Multi jawaban - get text for each index
                            $jawabanTexts = [];
                            foreach ($item->jawaban_benar as $jawabanIndex) {
                                if (isset($opsiJawaban[$jawabanIndex])) {
                                    $jawabanTexts[] = $opsiJawaban[$jawabanIndex];
                                }
                            }
                            $jawabanStr = implode(', ', $jawabanTexts);
                        } elseif ($item->jawaban_benar !== null && isset($opsiJawaban[$item->jawaban_benar])) {
                            // Pilihan ganda - get text for single index
                            $jawabanStr = $opsiJawaban[$item->jawaban_benar];
                        }
                    }
                    
                    // Fallback to kunci_jawaban if still empty
                    if (empty($jawabanStr) && !empty($item->kunci_jawaban)) {
                        $jawabanStr = $item->kunci_jawaban;
                    }

                    $sheet->setCellValue("A{$dataRow}", $index + 1);
                    $sheet->setCellValue("B{$dataRow}", strip_tags($item->pertanyaan));
                    $sheet->setCellValue("C{$dataRow}", $tipeSoalMap[$item->tipe_soal] ?? ucfirst(str_replace('_', ' ', $item->tipe_soal)));
                    $sheet->setCellValue("D{$dataRow}", $opsiStr);
                    $sheet->setCellValue("E{$dataRow}", $jawabanStr);
                    $sheet->setCellValue("F{$dataRow}", $item->kategori ? $item->kategori->judul : ($item->kursus ? $item->kursus->judul : '-'));
                    $sheet->setCellValue("G{$dataRow}", $item->poin ?? 1);
                    $sheet->setCellValue("H{$dataRow}", $item->creator ? $item->creator->name : '-');
                    $sheet->setCellValue("I{$dataRow}", $item->created_at->format('d/m/Y'));

                    // Alternate row colors
                    if ($index % 2 == 1) {
                        $sheet->getStyle("A{$dataRow}:I{$dataRow}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFC');
                    }

                    $sheet->getRowDimension($dataRow)->setRowHeight(22);
                    $dataRow++;
                }

                $lastDataRow = $dataRow - 1;

                // Apply borders to data table
                if ($this->soal->count() > 0) {
                    $sheet->getStyle("A11:I{$lastDataRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                        ],
                    ]);
                }

                // Column widths
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(50);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(40);
                $sheet->getColumnDimension('E')->setWidth(20);
                $sheet->getColumnDimension('F')->setWidth(25);
                $sheet->getColumnDimension('G')->setWidth(10);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(15);

                // Center align specific columns
                $sheet->getStyle("A12:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C12:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("G12:G{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I12:I{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Word wrap for long text
                $sheet->getStyle("B12:B{$lastDataRow}")->getAlignment()->setWrapText(true);
                $sheet->getStyle("D12:D{$lastDataRow}")->getAlignment()->setWrapText(true);

                // Freeze header row
                $sheet->freezePane('A12');
            },
        ];
    }
}
