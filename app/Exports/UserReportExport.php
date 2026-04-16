<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UserReportExport implements FromCollection, WithEvents
{
    public function __construct(
        private Collection $data,
        private string $filterRole,
        private string $tglDari,
        private string $tglSampai,
        private string $exportedBy
    ) {}

    public function collection(): Collection
    {
        return collect([]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet   = $event->sheet->getDelegate();
                $lastCol = 'H';

                /*
                =========================
                JUDUL
                =========================
                */
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'LAPORAN USER — SIPORA');

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2563eb']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(30);

                /*
                =========================
                FILTER INFO
                =========================
                */
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue(
                    'A2',
                    'Role: ' . $this->filterRole .
                    ' | Periode: ' . $this->tglDari . ' s/d ' . $this->tglSampai
                );

                /*
                =========================
                EXPORT INFO
                =========================
                */
                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue(
                    'A3',
                    'Diekspor oleh: ' . $this->exportedBy .
                    ' | ' . now()->format('d M Y, H:i') . ' WIB' .
                    ' | Total: ' . $this->data->count() . ' user'
                );

                $sheet->getStyle("A2:A3")->applyFromArray([
                    'font' => ['size' => 10],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ]
                ]);

                /*
                =========================
                HEADER TABLE
                =========================
                */
                $headers = [
                    'No',
                    'Nama Lengkap',
                    'Username',
                    'NIM',
                    'Email',
                    'Role',
                    'Status',
                    'Tanggal Daftar'
                ];

                $cols = range('A', 'H');

                foreach ($headers as $i => $h) {
                    $sheet->setCellValue($cols[$i] . '5', $h);
                }

                $sheet->getStyle("A5:{$lastCol}5")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1e40af']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER
                    ],
                ]);

                $sheet->getRowDimension(5)->setRowHeight(22);

                /*
                =========================
                DATA
                =========================
                */
                $row = 6;

                foreach ($this->data as $i => $u) {

                    $sheet->setCellValue("A{$row}", $i + 1);
                    $sheet->setCellValue("B{$row}", $u->nama_lengkap);
                    $sheet->setCellValue("C{$row}", $u->username);
                    $sheet->setCellValue("D{$row}", $u->nim ?? '-');
                    $sheet->setCellValue("E{$row}", $u->email);
                    $sheet->setCellValue("F{$row}", ucfirst($u->role));
                    $sheet->setCellValue("G{$row}", ucfirst($u->status));
                    $sheet->setCellValue(
                        "H{$row}",
                        $u->created_at
                            ? \Carbon\Carbon::parse($u->created_at)->format('d M Y')
                            : '-'
                    );

                    // Zebra row
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()
                            ->setRGB('f8fafc');
                    }

                    $row++;
                }

                /*
                =========================
                BORDER
                =========================
                */
                if ($row > 6) {
                    $sheet->getStyle("A5:{$lastCol}" . ($row - 1))
                        ->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                }

                /*
                =========================
                ALIGNMENT
                =========================
                */
                foreach (range(6, $row - 1) as $r) {

                    foreach (['A','F','G','H'] as $col) {
                        $sheet->getStyle("{$col}{$r}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }

                    $sheet->getStyle("A{$r}:{$lastCol}{$r}")
                        ->getAlignment()
                        ->setVertical(Alignment::VERTICAL_CENTER);
                }

                /*
                =========================
                AUTO FILTER
                =========================
                */
                if ($row > 6) {
                    $sheet->setAutoFilter("A5:{$lastCol}" . ($row - 1));
                }

                /*
                =========================
                FREEZE HEADER
                =========================
                */
                $sheet->freezePane('A6');

                /*
                =========================
                FINAL FIX WIDTH (PENTING)
                =========================
                */
                $sheet->getColumnDimension('A')->setWidth(6);
                $sheet->getColumnDimension('B')->setWidth(28);
                $sheet->getColumnDimension('C')->setWidth(18);
                $sheet->getColumnDimension('D')->setWidth(16);
                $sheet->getColumnDimension('E')->setWidth(32);
                $sheet->getColumnDimension('F')->setWidth(14);
                $sheet->getColumnDimension('G')->setWidth(14);
                $sheet->getColumnDimension('H')->setWidth(18);

                // Biar teks tidak kepotong aneh
                $sheet->getStyle("A5:H{$row}")
                    ->getAlignment()
                    ->setWrapText(false);
            }
        ];
    }
}