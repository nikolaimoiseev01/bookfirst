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
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->foreignId('print_order_status_id')->nullable()->references('id')->on('print_order_statuses');
            $table->nullableMorphs('model');
            $table->bigInteger('books_cnt')->nullable();
            $table->string('inside_color')->nullable();
            $table->bigInteger('color_pages')->nullable();
            $table->string('cover_type')->nullable();
            $table->string('full_name')->nullable();
            $table->string('telephone')->nullable();
            $table->string('country')->nullable();
            $table->foreignId('address_type_id')->nullable()->references('id')->on('address_types');
            $table->json('address');
            $table->dateTime('paid_at')->nullable();
            $table->string('track_number')->nullable();
            $table->foreignId('logistic_company_id')->nullable()->references('id')->on('logistic_companies');
            $table->foreignId('printing_company_id')->nullable()->references('id')->on('printing_companies');
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
