<?php

namespace App\Services;

class DocumentScreeningService
{
    public function analyze(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        if ($extension !== 'docx') {
            return [
                'supported' => false,
                'passed' => false,
                'score' => 0,
                'message' => 'Screening format otomatis saat ini mendukung file DOCX.',
                'checks' => [
                    'heading' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).'],
                    'paper_size' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).'],
                    'margin' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).'],
                ],
            ];
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [
                'supported' => true,
                'passed' => false,
                'score' => 0,
                'message' => 'Gagal membaca struktur file DOCX.',
                'checks' => [],
            ];
        }

        $documentXml = $zip->getFromName('word/document.xml') ?: '';
        $settingsXml = $zip->getFromName('word/settings.xml') ?: '';
        $zip->close();

        $headingPassed = preg_match('/w:pStyle\s+w:val="Heading[1-3]"/i', $documentXml) === 1;

        $paperPassed = false;
        if (preg_match('/w:pgSz[^>]*w:w="(\d+)"[^>]*w:h="(\d+)"/i', $settingsXml, $matches)) {
            $w = (int) $matches[1];
            $h = (int) $matches[2];
            $a4 = [[11906, 16838], [16838, 11906]];
            foreach ($a4 as [$aw, $ah]) {
                if (abs($w - $aw) <= 300 && abs($h - $ah) <= 300) {
                    $paperPassed = true;
                    break;
                }
            }
        }

        $marginPassed = false;
        if (preg_match('/w:pgMar[^>]*w:top="(\d+)"[^>]*w:right="(\d+)"[^>]*w:bottom="(\d+)"[^>]*w:left="(\d+)"/i', $settingsXml, $mm)) {
            $top = (int) $mm[1];
            $right = (int) $mm[2];
            $bottom = (int) $mm[3];
            $left = (int) $mm[4];

            $target = 1440;
            $tolerance = 240;
            $marginPassed = abs($top - $target) <= $tolerance
                && abs($right - $target) <= $tolerance
                && abs($bottom - $target) <= $tolerance
                && abs($left - $target) <= $tolerance;
        }

        $checks = [
            'heading' => [
                'passed' => $headingPassed,
                'message' => $headingPassed ? 'Struktur heading terdeteksi (Heading 1/2/3).' : 'Heading style (Heading 1/2/3) tidak terdeteksi.',
            ],
            'paper_size' => [
                'passed' => $paperPassed,
                'message' => $paperPassed ? 'Ukuran kertas terdeteksi A4.' : 'Ukuran kertas belum sesuai A4.',
            ],
            'margin' => [
                'passed' => $marginPassed,
                'message' => $marginPassed ? 'Margin terdeteksi sekitar 2.54cm (normal).' : 'Margin belum sesuai standar sekitar 2.54cm.',
            ],
        ];

        $passedCount = count(array_filter($checks, fn ($check) => $check['passed']));
        $score = (int) round(($passedCount / max(1, count($checks))) * 100);

        return [
            'supported' => true,
            'passed' => $score >= 67,
            'score' => $score,
            'message' => $score >= 67
                ? 'Dokumen lolos screening format dasar.'
                : 'Dokumen belum memenuhi screening format dasar.',
            'checks' => $checks,
        ];
    }
}
