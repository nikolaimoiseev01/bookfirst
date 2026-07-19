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
        Schema::create('email_campaign_recipients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('email_campaign_id');
            $table->bigInteger('email_recipient_id');
            $table->string('mailganer_status')->default('pending');
            $table->string('mailganer_click_link')->nullable();
            $table->text('mailganer_reason')->nullable();
            $table->timestamps();

            $table->index('email_campaign_id');
            $table->index('email_recipient_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
    }
};
