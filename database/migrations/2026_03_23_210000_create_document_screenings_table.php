<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('document_screenings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('dokumen_id');
            $table->boolean('passed')->default(false);
            $table->unsignedTinyInteger('score')->default(0);
            $table->json('checks_json')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('dokumen_id');
            $table->foreign('dokumen_id')->references('dokumen_id')->on('dokumen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_screenings');
    }
};
