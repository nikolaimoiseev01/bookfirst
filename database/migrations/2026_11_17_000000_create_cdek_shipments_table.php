<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cdek_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('own_book_ids');
            $table->json('book_titles');
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->unsignedInteger('weight');
            $table->unsignedSmallInteger('length');
            $table->unsignedSmallInteger('width');
            $table->unsignedSmallInteger('height');
            $table->unsignedInteger('tariff_code');
            $table->string('status')->default('pending');
            $table->string('cdek_uuid')->nullable();
            $table->string('cdek_number')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cdek_shipments');
    }
};
