<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('master_sort_options')) {
            return;
        }

        Schema::create('master_sort_options', function (Blueprint $table) {
            $table->increments('id_sort');
            $table->string('sort_key', 50)->unique();
            $table->string('sort_label', 100);
            $table->string('sort_scope', 20)->default('both')->index();
            $table->unsignedSmallInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_sort_options');
    }
};
