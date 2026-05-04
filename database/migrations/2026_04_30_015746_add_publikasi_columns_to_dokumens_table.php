<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->timestamp('published_at')->nullable()->after('tgl_unggah');
            $table->string('nomor_surat', 100)->nullable()->after('published_at');
            $table->boolean('is_published')->default(false)->after('nomor_surat');
        });
    }

    public function down(): void
    {
        // FIXED: was 'dokumens' (typo), correct table name is 'dokumen'
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn(['published_at', 'nomor_surat', 'is_published']);
        });
    }
};
