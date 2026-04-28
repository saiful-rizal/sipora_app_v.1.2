<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('master_user_roles')) {
            return;
        }

        Schema::create('master_user_roles', function (Blueprint $table) {
            $table->increments('id_role');
            $table->string('role_key', 50)->unique();
            $table->string('role_label', 100);
            $table->unsignedSmallInteger('role_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_user_roles');
    }
};
