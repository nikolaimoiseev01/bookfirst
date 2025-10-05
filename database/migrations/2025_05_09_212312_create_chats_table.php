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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_created')->references('id')->on('users');
            $table->bigInteger('user_to');
            $table->string('title');
            $table->foreignId('chat_status_id')->references('id')->on('chat_statuses');
            $table->nullableMorphs('model');
            $table->integer('flg_admin_chat');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
