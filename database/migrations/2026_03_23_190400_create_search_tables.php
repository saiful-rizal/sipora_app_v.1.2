<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('search_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('keyword', 255);
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->dateTime('deleted_at')->nullable();
        });

        Schema::create('trending_keywords', function (Blueprint $table) {
            $table->increments('id');
            $table->string('keyword', 255)->unique();
            $table->integer('search_count')->default(1);
            $table->dateTime('last_searched')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trending_keywords');
        Schema::dropIfExists('search_history');
    }
};
