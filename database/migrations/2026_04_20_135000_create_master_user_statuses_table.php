<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('master_user_statuses')) {
            return;
        }

        Schema::create('master_user_statuses', function (Blueprint $table) {
            $table->increments('id_status');
            $table->string('status_key', 50)->unique();
            $table->string('status_label', 100);
            $table->unsignedSmallInteger('status_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_user_statuses');
    }
};
