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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['CREATED', 'FAILED', 'CONFIRMED'])->default('CREATED');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('type');
            $table->string('description', 255)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payment_method' )->nullable();
            $table->string('yoo_id' )->nullable();
            $table->nullableMorphs('model');
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
