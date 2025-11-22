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
        Schema::create('ext_promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('status');
            $table->string('login');
            $table->string('password');
            $table->string('site');
            $table->integer('days');
            $table->integer('price_total');
            $table->integer('price_executor');
            $table->integer('price_our');
            $table->foreignId('promocode_id')->nullable()->references('id')->on('promocodes');
            $table->dateTime('paid_at')->nullable();
            $table->string('started_at')->nullable();
            $table->text('comment')->nullable();
            $table->integer('executor_got_payment')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ext_promotions');
    }
};
