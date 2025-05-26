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
        Schema::create('participations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')-> nullable();
            $table->foreignId('user_id')-> nullable();
            $table->string('author_name')-> nullable();
            $table->bigInteger('works_number')-> nullable();
            $table->bigInteger('rows')-> nullable();
            $table->bigInteger('pages')-> nullable();
            $table->foreignId('participation_status_id');
            $table->foreignId('promocode_id')-> nullable();
            $table->bigInteger('price_part')-> nullable();
            $table->bigInteger('price_print')-> nullable();
            $table->bigInteger('price_check')-> nullable();
            $table->bigInteger('price_send')-> nullable();
            $table->bigInteger('price_total')-> nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participations');
    }
};
