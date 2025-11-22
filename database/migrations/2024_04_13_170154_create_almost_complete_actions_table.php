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
        Schema::create('almost_complete_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('almost_complete_action_type_id');
            $table->foreignId('collection_id')->nullable();
            $table->foreignId('own_book_id')->nullable();
            $table->dateTime('dt_action_completed')->nullable();
            $table->integer('cnt_email_sent')->nullable();
            $table->dateTime('dt_last_email_sent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('almost_complete_actions');
    }
};
