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
        Schema::create('inner_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inner_task_status_id')->nullable();
            $table->foreignId('collection_id')->nullable();
            $table->foreignId('own_book_id')->nullable();
            $table->string('responsible')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->dateTime('deadline_inner')->nullable();
            $table->boolean('custom_task_flg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inner_tasks');
    }
};
