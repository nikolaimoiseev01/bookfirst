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
        Schema::create('cdek_cities', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("city")->nullable();
            $table->string("country_code")->nullable();
            $table->string("country")->nullable();
            $table->string("region")->nullable();
            $table->string("region_code")->nullable();
            $table->string("sub_region")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdek_cities');
    }
};
