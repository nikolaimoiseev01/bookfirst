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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_short');
            $table->string('slug');
            $table->foreignId('collection_status_id')->constrained();
            $table->integer('pages')->nullable();
            $table->text('description')->nullable();
            $table->date('date_apps_end')->nullable();
            $table->date('date_preview')->nullable();
            $table->date('date_voting_end')->nullable();
            $table->date('date_print_start')->nullable();
            $table->date('date_print_end')->nullable();
            $table->json('winner_participations')->nullable();
            $table->json('links')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
