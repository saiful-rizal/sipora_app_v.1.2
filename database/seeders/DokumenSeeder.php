<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DokumenSeeder extends Seeder
{
    public function run(): void
    {
        $existingCount = DB::table('dokumen')->count();
        $targetTotal = 100;
        $itemsToCreate = max(0, $targetTotal - $existingCount);

        if ($itemsToCreate === 0) {
            return;
        }

        $users = DB::table('users')
            ->whereIn('role', ['mahasiswa', 'admin', 'superadmin'])
            ->orderBy('id_user')
            ->get()
            ->all();

        $divisions = DB::table('master_divisi')->pluck('id_divisi')->all();
        $years = DB::table('master_tahun')->pluck('year_id')->all();
        $statuses = DB::table('master_status_dokumen')->pluck('status_id')->all();
        $themes = DB::table('master_tema')->pluck('id_tema')->all();
        $majors = DB::table('master_jurusan')->pluck('id_jurusan')->all();
        $files = collect(array_merge(
            glob(public_path('uploads/documents') . '/*.pdf') ?: [],
            glob(public_path('uploads/documents') . '/*.docx') ?: []
        ))
            ->map(fn ($path) => basename($path))
            ->values()
            ->all();
        $turnitinFiles = collect(array_merge(
            glob(public_path('uploads/turnitin') . '/*.pdf') ?: [],
            glob(public_path('uploads/turnitin') . '/*.docx') ?: []
        ))
            ->map(fn ($path) => basename($path))
            ->values()
            ->all();

        if (empty($users) || empty($divisions) || empty($years) || empty($statuses) || empty($themes) || empty($majors) || empty($files)) {
            return;
        }

        $titlePrefixes = ['Analisis', 'Implementasi', 'Rancang Bangun', 'Evaluasi', 'Pengembangan', 'Optimasi', 'Penerapan'];
        $titleSubjects = ['Sistem Informasi', 'Machine Learning', 'Web', 'Aplikasi Mobile', 'Manajemen Data', 'Repository Akademik', 'SIPORA'];
        $titleObjects = ['pada', 'untuk', 'berbasis', 'di', 'dalam'];
        $titleTopics = ['Repository Dokumen', 'Klasifikasi Dokumen', 'Manajemen File', 'Turnitin Otomatis', 'AI Screening', 'Pencarian Dokumen', 'Notifikasi Dokumen'];

        for ($i = 1; $i <= $itemsToCreate; $i++) {
            $uploader = $users[array_rand($users)];
            $jurusanId = $majors[array_rand($majors)];
            $prodiIds = DB::table('master_prodi')->where('id_jurusan', $jurusanId)->pluck('id_prodi')->all();
            if (empty($prodiIds)) {
                continue;
            }

            $divisiId = $divisions[array_rand($divisions)];
            $temaId = $themes[array_rand($themes)];
            $yearId = $years[array_rand($years)];
            $statusId = $statuses[array_rand($statuses)];
            $filePath = $files[array_rand($files)];
            $turnitinFile = !empty($turnitinFiles) && rand(0, 1) === 1 ? $turnitinFiles[array_rand($turnitinFiles)] : null;

            $title = sprintf(
                '%s %s %s %s %s',
                $titlePrefixes[array_rand($titlePrefixes)],
                $titleSubjects[array_rand($titleSubjects)],
                $titleObjects[array_rand($titleObjects)],
                $titleTopics[array_rand($titleTopics)],
                $i
            );

            $docId = DB::table('dokumen')->insertGetId([
                'judul' => $title,
                'abstrak' => 'Dokumen contoh untuk pengujian SIPORA nomor ' . $i . '. ' . Str::random(120),
                'turnitin' => rand(0, 45),
                'turnitin_file' => $turnitinFile,
                'kata_kunci' => 'sipora, dokumen, ' . Str::slug($title, ','),
                'file_path' => $filePath,
                'tgl_unggah' => now()->subDays(rand(0, 365))->subMinutes(rand(0, 1440)),
                'uploader_id' => $uploader->id_user,
                'id_tema' => $temaId,
                'id_jurusan' => $jurusanId,
                'id_prodi' => $prodiIds[array_rand($prodiIds)],
                'id_divisi' => $divisiId,
                'year_id' => $yearId,
                'status_id' => $statusId,
            ], 'dokumen_id');

            $score = rand(55, 100);
            DB::table('document_screenings')->insert([
                'dokumen_id' => $docId,
                'passed' => $score >= 70,
                'score' => $score,
                'checks_json' => json_encode([
                    'heading' => ['passed' => true, 'message' => 'Heading terdeteksi.'],
                    'footer' => ['passed' => true, 'message' => 'Footer terdeteksi.'],
                    'margin' => ['passed' => true, 'message' => 'Margin sesuai.'],
                    'spacing_padding' => ['passed' => true, 'message' => 'Spacing sesuai.'],
                    'font' => ['passed' => true, 'message' => 'Font sesuai.'],
                ], JSON_UNESCAPED_UNICODE),
                'message' => 'Seeder dokumen contoh dibuat otomatis.',
                'created_at' => now(),
            ]);

            if (DB::getSchemaBuilder()->hasTable('document_yolo_screenings')) {
                DB::table('document_yolo_screenings')->insert([
                    'dokumen_id' => $docId,
                    'model_used' => 'yolov8n.pt',
                    'required_classes' => 'heading,title,paragraph',
                    'ocr_lang' => 'eng+ind',
                    'detected_classes' => json_encode(['heading', 'paragraph', 'footer'], JSON_UNESCAPED_UNICODE),
                    'format_passed' => true,
                    'pages_count' => rand(5, 20),
                    'ocr_text_sample' => 'Contoh hasil OCR untuk dokumen seeder SIPORA.',
                    'result_json' => json_encode(['seeded' => true, 'dokumen_id' => $docId], JSON_UNESCAPED_UNICODE),
                    'result_file' => '',
                    'created_at' => now(),
                ]);
            }

            $summaryMessage = '<strong>' . e($uploader->username) . '</strong> mengunggah dokumen: "' . e($title) . '"';

            DB::table('notifications')->insert([
                'user_id' => null,
                'actor_id' => $uploader->id_user,
                'doc_id' => $docId,
                'type' => 'upload',
                'title' => 'Dokumen Baru',
                'message' => $summaryMessage,
                'icon_type' => 'info',
                'icon_class' => 'bi-file-earmark-plus',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            DB::table('notifications')->insert([
                'user_id' => $uploader->id_user,
                'actor_id' => $uploader->id_user,
                'doc_id' => $docId,
                'type' => 'upload_confirm',
                'title' => 'Upload Berhasil',
                'message' => 'Dokumen "' . e($title) . '" berhasil diunggah.',
                'icon_type' => 'success',
                'icon_class' => 'bi-check-circle-fill',
                'is_read' => 0,
                'created_at' => now(),
            ]);

            DB::table('notifikasi')->insert([
                'user_id' => $uploader->id_user,
                'judul' => 'Upload Berhasil',
                'isi' => 'Dokumen "' . $title . '" berhasil diunggah.',
                'status' => 'unread',
                'waktu' => now(),
            ]);
        }
    }
}