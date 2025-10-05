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
        Schema::create('cdek_offices', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('country_code')->nullable();
            $table->string('region_code')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->json('full_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdek_offices');
    }
};
