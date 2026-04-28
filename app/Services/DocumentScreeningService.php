<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class DocumentScreeningService
{
    public function analyze(string $filePath): array
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $passScoreThreshold = max(1, min(100, (int) config('document_screening.pass_score', 70)));

        $baseChecks = $this->analyzeBaseChecks($filePath, $extension);
        $yoloOcr = $this->analyzeWithYoloAndOcr($filePath);

        $checks = $baseChecks['checks'];
        if (!empty($yoloOcr['checks']) && is_array($yoloOcr['checks'])) {
            foreach ($yoloOcr['checks'] as $key => $value) {
                if (!is_array($value) || !array_key_exists('passed', $value)) {
                    continue;
                }
                $checks[$key] = [
                    'passed' => (bool) $value['passed'],
                    'message' => (string) ($value['message'] ?? ''),
                    'source' => (string) ($value['source'] ?? 'yolo_ocr'),
                ];
            }
        }

        $requiredCheckKeys = ['heading', 'footer', 'paper_size', 'margin', 'spacing_padding', 'structure', 'font'];
        foreach ($requiredCheckKeys as $key) {
            if (!isset($checks[$key])) {
                $checks[$key] = [
                    'passed' => false,
                    'message' => 'Pemeriksaan belum tersedia.',
                    'source' => 'unknown',
                ];
            }
        }

        $passedCount = 0;
        foreach ($requiredCheckKeys as $key) {
            if (!empty($checks[$key]['passed'])) {
                $passedCount++;
            }
        }

        $score = (int) round(($passedCount / count($requiredCheckKeys)) * 100);
        $passed = $score >= $passScoreThreshold;

        $failedChecks = [];
        foreach ($requiredCheckKeys as $key) {
            if (empty($checks[$key]['passed'])) {
                $failedChecks[] = $key;
            }
        }

        $recommendations = $this->buildRecommendations($checks);

        $messages = [];
        if (!empty($baseChecks['message'])) {
            $messages[] = $baseChecks['message'];
        }
        if (!empty($yoloOcr['message'])) {
            $messages[] = $yoloOcr['message'];
        }

        return [
            'supported' => $baseChecks['supported'] || !empty($yoloOcr['available']),
            'passed' => $passed,
            'score' => $score,
            'message' => $passed
                ? 'Dokumen lolos screening format (heading, footer, margin, spacing/padding, struktur, font). Hasil kesesuaian: ' . $score . '%.'
                : (implode(' | ', array_filter($messages)) ?: 'Dokumen belum memenuhi screening format.') . ' (Batas lulus: ' . $passScoreThreshold . '%)',
            'pass_score_threshold' => $passScoreThreshold,
            'required_checks' => $requiredCheckKeys,
            'failed_checks' => $failedChecks,
            'recommendations' => $recommendations,
            'checks' => $checks,
            'yolo_ocr' => $yoloOcr,
        ];
    }

    private function buildRecommendations(array $checks): array
    {
        $map = [
            'heading' => 'Gunakan style Heading 1/2/3 secara konsisten pada judul bab/subbab.',
            'footer' => 'Gunakan footer konsisten dan atur jarak footer sesuai template dokumen.',
            'paper_size' => 'Pastikan ukuran kertas A4 pada pengaturan dokumen.',
            'margin' => 'Set margin sekitar 2.54cm (atas, kanan, bawah, kiri).',
            'spacing_padding' => 'Atur line spacing, paragraph spacing, dan indent/padding paragraf agar konsisten sesuai template.',
            'structure' => 'Lengkapi struktur minimal: judul bab, abstrak, metode, hasil, pembahasan, kesimpulan.',
            'font' => 'Gunakan font standar seperti Times New Roman/Calibri/Arial/Cambria secara konsisten.',
            'yolo_required_classes' => 'Periksa kualitas layout dokumen agar elemen wajib dapat terdeteksi model.',
        ];

        $recommendations = [];
        foreach ($checks as $key => $check) {
            if (!empty($check['passed'])) {
                continue;
            }

            $recommendations[] = [
                'check' => (string) $key,
                'message' => (string) ($check['message'] ?? ''),
                'action' => $map[$key] ?? 'Perbaiki format pada bagian ini sesuai panduan template dokumen.',
            ];
        }

        return $recommendations;
    }

    private function analyzeBaseChecks(string $filePath, string $extension): array
    {
        if ($extension !== 'docx') {
            return [
                'supported' => false,
                'message' => 'Pemeriksaan struktur XML hanya tersedia untuk file DOCX.',
                'checks' => [
                    'heading' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'footer' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'paper_size' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'margin' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'spacing_padding' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'structure' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                    'font' => ['passed' => false, 'message' => 'Tidak dapat dianalisis (bukan DOCX).', 'source' => 'xml'],
                ],
            ];
        }

        if (!class_exists('ZipArchive')) {
            return [
                'supported' => false,
                'message' => 'Ekstensi PHP ZipArchive tidak tersedia, pemeriksaan XML DOCX dilewati.',
                'checks' => [
                    'heading' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'footer' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'paper_size' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'margin' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'spacing_padding' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'structure' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                    'font' => ['passed' => false, 'message' => 'ZipArchive tidak tersedia di server PHP.', 'source' => 'xml'],
                ],
            ];
        }

        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return [
                'supported' => true,
                'message' => 'Gagal membaca struktur file DOCX.',
                'checks' => [
                    'heading' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'footer' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'paper_size' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'margin' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'spacing_padding' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'structure' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                    'font' => ['passed' => false, 'message' => 'Gagal membaca dokumen DOCX.', 'source' => 'xml'],
                ],
            ];
        }

        $documentXml = $zip->getFromName('word/document.xml') ?: '';
        $settingsXml = $zip->getFromName('word/settings.xml') ?: '';
        $stylesXml = $zip->getFromName('word/styles.xml') ?: '';
        $zip->close();

        $layoutXml = $settingsXml . ' ' . $documentXml;
        $twipToCm = static fn (int $twip): float => round($twip / 567, 2);

        $headingMatches = [];
        preg_match_all('/w:pStyle[^>]*w:val="Heading([1-3])"/i', $documentXml, $headingMatches);
        $headingCount = count($headingMatches[0] ?? []);
        $headingPassed = $headingCount > 0;

        $footerRefCount = preg_match_all('/w:footerReference\b/i', $documentXml);
        $footerDistanceTwips = null;
        if (preg_match('/w:pgMar[^>]*w:footer="(\d+)"/i', $layoutXml, $footerMatch)) {
            $footerDistanceTwips = (int) $footerMatch[1];
        }
        $footerPassed = $footerRefCount > 0;

        $paragraphCount = preg_match_all('/<w:p[ >]/i', $documentXml);
        $minParagraphs = max(1, (int) config('document_screening.min_paragraphs', 5));
        $structurePassed = $headingPassed && $paragraphCount >= $minParagraphs;

        $paperPassed = false;
        if (preg_match('/w:pgSz[^>]*w:w="(\d+)"[^>]*w:h="(\d+)"/i', $layoutXml, $matches)) {
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
        $top = null;
        $right = null;
        $bottom = null;
        $left = null;
        if (preg_match('/w:pgMar[^>]*w:top="(\d+)"[^>]*w:right="(\d+)"[^>]*w:bottom="(\d+)"[^>]*w:left="(\d+)"/i', $layoutXml, $mm)) {
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

        $fontNames = [];
        if (preg_match_all('/w:rFonts[^>]*w:ascii="([^"]+)"/i', $documentXml . ' ' . $stylesXml, $fontMatches)) {
            $fontNames = array_values(array_unique(array_map('trim', $fontMatches[1])));
        }

        $fontSizesPt = [];
        if (preg_match_all('/w:sz[^>]*w:val="(\d+)"/i', $documentXml . ' ' . $stylesXml, $fontSizeMatches)) {
            $fontSizesPt = array_values(array_unique(array_map(static fn ($v) => round(((int) $v) / 2, 1), $fontSizeMatches[1])));
            sort($fontSizesPt);
        }

        $spacingXml = $documentXml . ' ' . $stylesXml;
        $lineSpacing = null;
        $lineRule = 'auto';
        if (preg_match('/w:spacing[^>]*w:line="(\d+)"/i', $spacingXml, $lineMatch)) {
            $lineSpacing = round(((int) $lineMatch[1]) / 240, 2);
        }
        if (preg_match('/w:spacing[^>]*w:lineRule="([^"]+)"/i', $spacingXml, $lineRuleMatch)) {
            $lineRule = (string) $lineRuleMatch[1];
        }

        $spaceBeforeTwips = 0;
        $spaceAfterTwips = 0;
        if (preg_match('/w:spacing[^>]*w:before="(\d+)"/i', $spacingXml, $beforeMatch)) {
            $spaceBeforeTwips = (int) $beforeMatch[1];
        }
        if (preg_match('/w:spacing[^>]*w:after="(\d+)"/i', $spacingXml, $afterMatch)) {
            $spaceAfterTwips = (int) $afterMatch[1];
        }

        $indentLeftTwips = 0;
        $indentRightTwips = 0;
        $indentFirstLineTwips = 0;
        if (preg_match('/w:ind[^>]*w:left="(\d+)"/i', $spacingXml, $indentLeftMatch)) {
            $indentLeftTwips = (int) $indentLeftMatch[1];
        }
        if (preg_match('/w:ind[^>]*w:right="(\d+)"/i', $spacingXml, $indentRightMatch)) {
            $indentRightTwips = (int) $indentRightMatch[1];
        }
        if (preg_match('/w:ind[^>]*w:firstLine="(\d+)"/i', $spacingXml, $indentFirstLineMatch)) {
            $indentFirstLineTwips = (int) $indentFirstLineMatch[1];
        }

        $spaceBeforePt = round($spaceBeforeTwips / 20, 1);
        $spaceAfterPt = round($spaceAfterTwips / 20, 1);
        $indentLeftCm = $twipToCm($indentLeftTwips);
        $indentRightCm = $twipToCm($indentRightTwips);
        $indentFirstLineCm = $twipToCm($indentFirstLineTwips);

        $spacingPaddingPassed = true;
        if ($lineSpacing !== null && ($lineSpacing < 1.0 || $lineSpacing > 2.5)) {
            $spacingPaddingPassed = false;
        }
        if ($spaceBeforePt > 24 || $spaceAfterPt > 24) {
            $spacingPaddingPassed = false;
        }
        if ($indentLeftCm > 2.5 || $indentRightCm > 2.5 || $indentFirstLineCm > 2.5) {
            $spacingPaddingPassed = false;
        }

        $allowedFonts = array_values(array_filter(array_map('trim', explode(',', (string) config('document_screening.allowed_fonts', 'Times New Roman,Calibri,Arial,Cambria')))));
        if (empty($allowedFonts)) {
            $allowedFonts = ['Times New Roman', 'Calibri', 'Arial', 'Cambria'];
        }
        $fontPassed = !empty($fontNames);
        if ($fontPassed) {
            $nonStandard = array_filter($fontNames, fn ($font) => !in_array($font, $allowedFonts, true));
            $fontPassed = count($nonStandard) <= 2;
        }

        $checks = [
            'heading' => [
                'passed' => $headingPassed,
                'message' => $headingPassed
                    ? 'Heading terdeteksi sebanyak ' . $headingCount . ' bagian.'
                    : 'Heading style (Heading 1/2/3) tidak terdeteksi.',
                'source' => 'xml',
            ],
            'footer' => [
                'passed' => $footerPassed,
                'message' => $footerPassed
                    ? 'Footer terdeteksi ' . $footerRefCount . ' section' . ($footerDistanceTwips !== null ? ' (jarak footer: ' . $twipToCm($footerDistanceTwips) . ' cm).' : '.')
                    : 'Footer tidak terdeteksi' . ($footerDistanceTwips !== null ? ' (jarak footer saat ini: ' . $twipToCm($footerDistanceTwips) . ' cm).' : '.'),
                'source' => 'xml',
            ],
            'paper_size' => [
                'passed' => $paperPassed,
                'message' => $paperPassed ? 'Ukuran kertas terdeteksi A4.' : 'Ukuran kertas belum sesuai A4.',
                'source' => 'xml',
            ],
            'margin' => [
                'passed' => $marginPassed,
                'message' => ($top !== null && $right !== null && $bottom !== null && $left !== null)
                    ? (
                        'Margin terdeteksi: atas ' . $twipToCm($top) . ' cm, kanan ' . $twipToCm($right) . ' cm, bawah ' . $twipToCm($bottom) . ' cm, kiri ' . $twipToCm($left) . ' cm.' .
                        ($marginPassed ? ' Sesuai standar.' : ' Belum sesuai standar sekitar 2.54 cm.')
                    )
                    : 'Margin belum dapat dibaca dari dokumen.',
                'source' => 'xml',
            ],
            'spacing_padding' => [
                'passed' => $spacingPaddingPassed,
                'message' => 'Spacing/Padding: line spacing ' . ($lineSpacing !== null ? $lineSpacing : '-') . ' (' . $lineRule . '), before ' . $spaceBeforePt . ' pt, after ' . $spaceAfterPt . ' pt, indent kiri ' . $indentLeftCm . ' cm, kanan ' . $indentRightCm . ' cm, first-line ' . $indentFirstLineCm . ' cm.' .
                    ($spacingPaddingPassed ? ' Sesuai format.' : ' Perlu penyesuaian format.'),
                'source' => 'xml',
            ],
            'structure' => [
                'passed' => $structurePassed,
                'message' => $structurePassed
                    ? 'Struktur dokumen terdeteksi baik (heading: ' . $headingCount . ', paragraf: ' . $paragraphCount . ').'
                    : 'Struktur dokumen belum konsisten (heading/paragraf belum memadai, minimal ' . $minParagraphs . ' paragraf).',
                'source' => 'xml',
            ],
            'font' => [
                'passed' => $fontPassed,
                'message' => $fontPassed
                    ? 'Font terdeteksi: ' . implode(', ', array_slice($fontNames, 0, 6)) . (count($fontSizesPt) > 0 ? ' | Ukuran font: ' . implode(', ', array_slice($fontSizesPt, 0, 6)) . ' pt' : '')
                    : 'Font dokumen tidak konsisten atau tidak terbaca.',
                'source' => 'xml',
            ],
        ];

        return [
            'supported' => true,
            'message' => 'Pemeriksaan struktur DOCX berhasil dijalankan.',
            'checks' => $checks,
        ];
    }

    private function analyzeWithYoloAndOcr(string $filePath): array
    {
        if (!config('document_screening.yolo_ocr_enabled', true)) {
            return [
                'available' => false,
                'message' => 'YOLOv8 + OCR dinonaktifkan melalui konfigurasi.',
                'checks' => [],
            ];
        }

        $scriptPath = base_path('scripts/doc_screening_yolo_ocr.py');
        if (!is_file($scriptPath)) {
            return [
                'available' => false,
                'message' => 'Script YOLOv8 + OCR belum tersedia.',
                'checks' => [],
            ];
        }

        $python = (string) config('document_screening.python', 'python');
        $model = (string) config('document_screening.model', 'yolov8n.pt');
        $ocrLang = (string) config('document_screening.ocr_lang', 'eng+ind');
        $requiredClasses = (string) config('document_screening.required_classes', 'heading,title,paragraph');
        $allowedFonts = (string) config('document_screening.allowed_fonts', 'Times New Roman,Calibri,Arial,Cambria');
        $minParagraphs = (int) config('document_screening.min_paragraphs', 5);
        $minSectionHits = (int) config('document_screening.min_section_hits', 3);
        $marginMinRatio = (float) config('document_screening.margin_min_ratio', 0.02);
        $maxPages = (int) config('document_screening.max_pages', 3);
        $passScore = (int) config('document_screening.pass_score', 70);
        $tesseractCmd = (string) config('document_screening.tesseract_cmd', '');
        $timeout = (int) config('document_screening.timeout', 120);

        $outputDir = storage_path('app/screenings');
        File::ensureDirectoryExists($outputDir);
        $outputFile = $outputDir . DIRECTORY_SEPARATOR . 'screening_' . uniqid() . '.json';

        $process = new Process([
            $python,
            $scriptPath,
            '--input',
            $filePath,
            '--output',
            $outputFile,
            '--ocr-lang',
            $ocrLang,
            '--model',
            $model,
            '--required-classes',
            $requiredClasses,
            '--allowed-fonts',
            $allowedFonts,
            '--min-paragraphs',
            (string) max(1, $minParagraphs),
            '--min-section-hits',
            (string) max(1, $minSectionHits),
            '--margin-min-ratio',
            (string) max(0.005, $marginMinRatio),
            '--max-pages',
            (string) max(1, $maxPages),
            '--pass-score',
            (string) max(1, min(100, $passScore)),
            '--tesseract-cmd',
            $tesseractCmd,
        ]);
        $process->setTimeout($timeout > 0 ? $timeout : 120);

        try {
            $process->run();
        } catch (\Throwable $e) {
            return [
                'available' => false,
                'message' => 'Eksekusi YOLOv8 + OCR gagal: ' . $e->getMessage(),
                'checks' => [],
            ];
        }

        if (!$process->isSuccessful() || !is_file($outputFile)) {
            return [
                'available' => false,
                'message' => 'YOLOv8 + OCR tidak berhasil dijalankan. ' . trim((string) $process->getErrorOutput()),
                'exit_code' => $process->getExitCode(),
                'checks' => [],
            ];
        }

        $raw = json_decode((string) file_get_contents($outputFile), true);
        if (!is_array($raw)) {
            return [
                'available' => false,
                'message' => 'Hasil YOLOv8 + OCR tidak valid.',
                'checks' => [],
                'result_file' => $outputFile,
            ];
        }

        return [
            'available' => true,
            'message' => (string) ($raw['message'] ?? 'YOLOv8 + OCR selesai diproses.'),
            'model_used' => (string) ($raw['model_used'] ?? $model),
            'required_classes' => (string) ($raw['required_classes'] ?? $requiredClasses),
            'ocr_lang' => (string) ($raw['ocr_lang'] ?? $ocrLang),
            'detected_classes' => array_values(array_unique((array) ($raw['detected_classes'] ?? []))),
            'format_passed' => (bool) ($raw['format_passed'] ?? false),
            'pages_count' => (int) ($raw['pages_count'] ?? 0),
            'ocr_text_sample' => (string) ($raw['ocr_text_sample'] ?? ''),
            'result_json' => json_encode($raw, JSON_UNESCAPED_UNICODE),
            'result_file' => $outputFile,
            'checks' => is_array($raw['checks'] ?? null) ? $raw['checks'] : [],
        ];
    }
}
