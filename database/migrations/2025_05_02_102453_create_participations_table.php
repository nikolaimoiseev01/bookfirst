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
            $table->foreignId('collection_id')->references('id')->on('collections');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('author_name');
            $table->bigInteger('works_number');
            $table->bigInteger('rows')->nullable();
            $table->bigInteger('pages');
            $table->foreignId('participation_status_id')->references('id')->on('participation_statuses');
            $table->bigInteger('print_order_id')->nullable();
            $table->foreignId('promocode_id')->nullable()->references('id')->on('promocodes');
            $table->bigInteger('price_part');
            $table->bigInteger('price_print')-> nullable();
            $table->bigInteger('price_check')-> nullable();
            $table->bigInteger('price_send')-> nullable();
            $table->bigInteger('price_total');
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
