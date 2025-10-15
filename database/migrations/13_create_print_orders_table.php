<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('print_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('print_order_status_id')->nullable();
            $table->nullableMorphs('model');
            $table->bigInteger('books_cnt')->nullable();
            $table->string('inside_color')->nullable();
            $table->bigInteger('pages_color')->nullable();
            $table->string('cover_type')->nullable();
            $table->bigInteger('price_print')->nullable();
            $table->bigInteger('price_send')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_telephone')->nullable();
            $table->string('country')->nullable();
            $table->bigInteger('address_type_id')->nullable();
            $table->json('address_json');
            $table->dateTime('paid_at')->nullable();
            $table->string('track_number')->nullable();
            $table->bigInteger('logistic_company_id')->nullable();
            $table->bigInteger('printing_company_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('print_orders');
    }
};
