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
        Schema::create('email_campaign_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_campaign_id')->constrained()->onDelete('cascade');
            $table->integer('total')->default(0);
            $table->integer('send_ok')->default(0);
            $table->integer('send_fail')->default(0);
            $table->integer('open_msg')->default(0);
            $table->integer('open_msg_uniq')->default(0);
            $table->integer('click_link')->default(0);
            $table->integer('click_link_uniq')->default(0);
            $table->integer('gen_ok')->default(0);
            $table->integer('dup')->default(0);
            $table->integer('bad')->default(0);
            $table->integer('fbl')->default(0);
            $table->integer('stop')->default(0);
            $table->integer('trap')->default(0);
            $table->integer('bounce')->default(0);
            $table->integer('spam')->default(0);
            $table->integer('unsubscribe')->default(0);
            $table->timestamps();

            $table->index('email_campaign_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaign_statistics');
    }
};
