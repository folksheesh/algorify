<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Http\Request;

class TransaksiExport implements WithEvents, WithTitle
{
    protected $request;
    protected $transaksi;
    protected $totalJumlah;
    protected $totalLunas;
    protected $totalPending;
    protected $totalGagal;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->loadData();
    }

    protected function loadData()
    {
        $query = Transaksi::with(['user', 'kursus'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('kursus', function($kq) use ($search) {
                      $kq->where('judul', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->request->filled('status')) {
            $status = $this->request->status;
            if ($status === 'lunas') {
                $query->where('status', 'success');
            } elseif ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'gagal') {
                $query->whereIn('status', ['expired', 'failed']);
            }
        }

        if ($this->request->filled('metode')) {
            $metodeMap = [
                'transfer bank' => 'bank_transfer',
                'e-wallet' => 'e_wallet',
                'kartu kredit' => 'credit_card',
                'qris' => 'qris',
                'mini market' => 'mini_market',
                'kartu debit' => 'kartu_debit',
            ];
            $metode = $metodeMap[strtolower($this->request->metode)] ?? $this->request->metode;
            $query->where('metode_pembayaran', $metode);
        }

        if ($this->request->filled('tanggal_mulai') && $this->request->filled('tanggal_akhir')) {
            $query->whereBetween('tanggal_transaksi', [
                $this->request->tanggal_mulai . ' 00:00:00',
                $this->request->tanggal_akhir . ' 23:59:59'
            ]);
        } elseif ($this->request->filled('tanggal_mulai')) {
            $query->where('tanggal_transaksi', '>=', $this->request->tanggal_mulai . ' 00:00:00');
        } elseif ($this->request->filled('tanggal_akhir')) {
            $query->where('tanggal_transaksi', '<=', $this->request->tanggal_akhir . ' 23:59:59');
        }

        $this->transaksi = $query->get();
        $this->totalJumlah = $this->transaksi->sum('jumlah');
        $this->totalLunas = $this->transaksi->where('status', 'success')->count();
        $this->totalPending = $this->transaksi->where('status', 'pending')->count();
        $this->totalGagal = $this->transaksi->whereIn('status', ['expired', 'failed'])->count();
    }

    public function title(): string
    {
        return 'Data Transaksi';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // === HEADER SECTION ===
                // Row 1: Title
                $sheet->setCellValue('A1', 'LAPORAN DATA TRANSAKSI - ALGORIFY');
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
                    ['Total Transaksi', $this->transaksi->count()],
                    ['Total Pendapatan', 'Rp ' . number_format($this->totalJumlah, 0, ',', '.')],
                    ['Transaksi Lunas', $this->totalLunas],
                    ['Transaksi Pending', $this->totalPending],
                    ['Transaksi Gagal', $this->totalGagal],
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
                $sheet->getStyle('A4:B9')->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                    ],
                ]);

                // Row 10: Empty
                $sheet->getRowDimension(10)->setRowHeight(10);

                // Row 11: Detail Title
                $sheet->setCellValue('A11', 'DETAIL TRANSAKSI');
                $sheet->mergeCells('A11:I11');
                $sheet->getStyle('A11')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '1E293B']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E0E7FF']],
                ]);
                $sheet->getRowDimension(11)->setRowHeight(25);

                // Row 12: Column Headers
                $headers = ['No', 'Kode Transaksi', 'Tanggal', 'Nama Peserta', 'Email', 'Kursus', 'Jumlah (Rp)', 'Metode Pembayaran', 'Status'];
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue("{$col}12", $header);
                    $col++;
                }
                $sheet->getStyle('A12:I12')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '5D3FFF']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(12)->setRowHeight(28);

                // Data rows starting from row 13
                $metodeMap = [
                    'bank_transfer' => 'Transfer Bank',
                    'e_wallet' => 'E-Wallet',
                    'credit_card' => 'Kartu Kredit',
                    'qris' => 'QRIS',
                    'mini_market' => 'Mini Market',
                    'kartu_debit' => 'Kartu Debit',
                ];

                $statusMap = [
                    'success' => 'Lunas',
                    'pending' => 'Pending',
                    'expired' => 'Kadaluarsa',
                    'failed' => 'Gagal',
                ];

                $dataRow = 13;
                foreach ($this->transaksi as $index => $item) {
                    $sheet->setCellValue("A{$dataRow}", $index + 1);
                    $sheet->setCellValue("B{$dataRow}", $item->kode_transaksi);
                    $sheet->setCellValue("C{$dataRow}", $item->tanggal_transaksi ? date('d/m/Y H:i', strtotime($item->tanggal_transaksi)) : '-');
                    $sheet->setCellValue("D{$dataRow}", $item->user->name ?? '-');
                    $sheet->setCellValue("E{$dataRow}", $item->user->email ?? '-');
                    $sheet->setCellValue("F{$dataRow}", $item->kursus->judul ?? '-');
                    $sheet->setCellValue("G{$dataRow}", 'Rp ' . number_format($item->jumlah, 0, ',', '.'));
                    $sheet->setCellValue("H{$dataRow}", $metodeMap[$item->metode_pembayaran] ?? ucfirst(str_replace('_', ' ', $item->metode_pembayaran ?? '-')));
                    $sheet->setCellValue("I{$dataRow}", $statusMap[$item->status] ?? ucfirst($item->status ?? '-'));

                    // Alternate row colors
                    if ($index % 2 == 1) {
                        $sheet->getStyle("A{$dataRow}:I{$dataRow}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F8FAFC');
                    }

                    // Status color coding
                    $statusCell = "I{$dataRow}";
                    if ($item->status === 'success') {
                        $sheet->getStyle($statusCell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('10B981'));
                        $sheet->getStyle($statusCell)->getFont()->setBold(true);
                    } elseif ($item->status === 'pending') {
                        $sheet->getStyle($statusCell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('F59E0B'));
                    } elseif (in_array($item->status, ['expired', 'failed'])) {
                        $sheet->getStyle($statusCell)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('EF4444'));
                    }

                    $sheet->getRowDimension($dataRow)->setRowHeight(22);
                    $dataRow++;
                }

                $lastDataRow = $dataRow - 1;

                // Apply borders to data table
                if ($this->transaksi->count() > 0) {
                    $sheet->getStyle("A12:I{$lastDataRow}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
                        ],
                    ]);
                }

                // Column widths
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(20);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(25);
                $sheet->getColumnDimension('E')->setWidth(30);
                $sheet->getColumnDimension('F')->setWidth(35);
                $sheet->getColumnDimension('G')->setWidth(18);
                $sheet->getColumnDimension('H')->setWidth(20);
                $sheet->getColumnDimension('I')->setWidth(15);

                // Center align specific columns
                $sheet->getStyle("A13:A{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("C13:C{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I13:I{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Freeze header row
                $sheet->freezePane('A13');
            },
        ];
    }
}
