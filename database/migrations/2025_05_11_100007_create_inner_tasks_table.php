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
            $table->string('type');
            $table->nullableMorphs('model');
            $table->string('responsible')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->dateTime('deadline_inner')->nullable();
            $table->boolean('flg_custom_task')->nullable();
            $table->boolean('flg_custom_finished')->nullable();
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
