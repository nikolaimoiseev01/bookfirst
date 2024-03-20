<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ext_promotion_parsed_readers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('ext_promotion_id');
            $table->dateTime('checked_at');
            $table->integer('readers_num');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ext_promotion_parsed_readers');
    }
};
