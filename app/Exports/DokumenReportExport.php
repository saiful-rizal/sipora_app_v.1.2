<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class DokumenReportExport implements FromCollection, WithStyles, ShouldAutoSize, WithEvents
{
    public function __construct(
        private Collection $data,
        private string $filterStatus = 'Semua Status',
        private string $tglDari = 'Awal',
        private string $tglSampai = 'Sekarang',
    ) {}

    public function collection(): Collection
    {
        // Kembalikan collection kosong karena data diisi manual di AfterSheet
        return collect([]);
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet   = $event->sheet->getDelegate();
                $lastCol = 'K';

                // ── BARIS 1: Judul Sistem ─────────────────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'LAPORAN DOKUMEN — SIPORA POLITEKNIK NEGERI JEMBER');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1d4ed8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(32);

                // ── BARIS 2: Keterangan Filter ────────────────────────────
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Status: ' . $this->filterStatus . '   |   Periode: ' . $this->tglDari . ' s/d ' . $this->tglSampai);
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1e3a8a']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'dbeafe']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(24);

                // ── BARIS 3: Tanggal Export & Total ──────────────────────
                $sheet->mergeCells("A3:{$lastCol}3");
                $sheet->setCellValue('A3', 'Diekspor pada: ' . now()->format('d M Y, H:i') . ' WIB   |   Total: ' . $this->data->count() . ' dokumen');
                $sheet->getStyle('A3')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '6b7280']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f3f4f6']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(18);

                // ── BARIS 4: Header Kolom ─────────────────────────────────
                $headers = ['No','Judul','Uploader','Tema','Jurusan','Prodi','Divisi','Tahun','Turnitin (%)','Tgl Unggah','Status'];
                $cols    = range('A', 'K');
                foreach ($headers as $i => $header) {
                    $sheet->setCellValue($cols[$i] . '4', $header);
                }
                $sheet->getStyle("A4:{$lastCol}4")->applyFromArray([
                    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e40af']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(4)->setRowHeight(20);

                // ── BARIS 5+: Data ────────────────────────────────────────
                $row = 5;
                foreach ($this->data as $i => $d) {
                    $sheet->setCellValue("A{$row}", $i + 1);
                    $sheet->setCellValue("B{$row}", $d->judul ?? '-');
                    $sheet->setCellValue("C{$row}", $d->uploader?->nama_lengkap ?? '-');
                    $sheet->setCellValue("D{$row}", $d->tema?->nama_tema ?? '-');
                    $sheet->setCellValue("E{$row}", $d->jurusan?->nama_jurusan ?? '-');
                    $sheet->setCellValue("F{$row}", $d->prodi?->nama_prodi ?? '-');
                    $sheet->setCellValue("G{$row}", $d->divisi?->nama_divisi ?? '-');
                    $sheet->setCellValue("H{$row}", $d->year?->tahun ?? '-');
                    $sheet->setCellValue("I{$row}", $d->turnitin ?? 0);
                    $sheet->setCellValue("J{$row}", $d->tgl_unggah
                        ? \Carbon\Carbon::parse($d->tgl_unggah)->format('d M Y') : '-');
                    $sheet->setCellValue("K{$row}", $d->status?->nama_status ?? '-');

                    // Warna selang-seling
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('f0f7ff');
                    }

                    $row++;
                }

                // ── BORDER semua data ─────────────────────────────────────
                if ($row > 5) {
                    $sheet->getStyle("A4:{$lastCol}" . ($row - 1))
                        ->getBorders()->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                }

                // ── CENTER kolom No, Tahun, Turnitin, Tgl, Status ─────────
                for ($r = 5; $r < $row; $r++) {
                    foreach (['A','H','I','J','K'] as $col) {
                        $sheet->getStyle("{$col}{$r}")
                            ->getAlignment()
                            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    }
                }
            },
        ];
    }
}
