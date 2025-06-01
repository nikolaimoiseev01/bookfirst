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
        Schema::create('collection_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participation_id_from')->references('id')->on('participations');
            $table->foreignId('collection_id')->references('id')->on('collections');
            $table->foreignId('participation_id_to')->references('id')->on('participations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_votes');
    }
};
