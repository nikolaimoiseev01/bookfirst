<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cdek_pickup_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->json('shipment_ids');
            $table->unsignedInteger('pickup_city_code');
            $table->text('pickup_address');
            $table->string('delivery_point');
            $table->string('pickup_contact_name');
            $table->string('pickup_contact_phone');
            $table->date('intake_date');
            $table->string('intake_time_from', 5);
            $table->string('intake_time_to', 5);
            $table->unsignedInteger('total_weight');
            $table->string('status')->default('processing');
            $table->string('order_status')->nullable();
            $table->string('intake_status')->nullable();
            $table->string('cdek_order_uuid')->nullable();
            $table->string('cdek_number')->nullable();
            $table->string('intake_uuid')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->json('intake_payload')->nullable();
            $table->json('intake_response_payload')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cdek_pickup_groups');
    }
};
