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
        Schema::create('own_book_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('own_book_id')->references('id')->on('own_books');
            $table->foreignId('work_id')->references('id')->on('works');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('own_book_works');
    }
};
