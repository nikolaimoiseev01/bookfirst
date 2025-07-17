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
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->foreignId('almost_complete_action_type_id')->references('id')->on('almost_complete_action_types');
            $table->string('model_type')->nullable();
            $table->integer('model_id')->nullable();
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
