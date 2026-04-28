<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('document_yolo_screenings')) {
            return;
        }

        Schema::create('document_yolo_screenings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dokumen_id')->index();
            $table->string('model_used', 100)->default('yolov8n.pt')->comment('YOLOv8 model yang digunakan');
            $table->string('required_classes', 255)->nullable()->comment('Classes wajib yang dicari (comma-separated)');
            $table->string('ocr_lang', 20)->default('eng')->comment('Bahasa OCR Tesseract');
            $table->json('detected_classes')->nullable()->comment('Classes yang terdeteksi');
            $table->boolean('format_passed')->default(false)->index()->comment('Apakah format dokumen sesuai');
            $table->unsignedSmallInteger('pages_count')->default(0)->comment('Jumlah halaman yang diproses');
            $table->text('ocr_text_sample')->nullable()->comment('Sampel text OCR (500 karakter pertama)');
            $table->longText('result_json')->nullable()->comment('Full result JSON dari YOLOv8 + OCR');
            $table->text('result_file')->nullable()->comment('Path ke file hasil JSON');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('dokumen_id')
                ->references('dokumen_id')
                ->on('dokumen')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_yolo_screenings');
    }
};
