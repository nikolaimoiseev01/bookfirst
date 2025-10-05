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
        Schema::create('cdek_regions', function (Blueprint $table) {
            $table->id();
            $table->string('country_code');
            $table->string('country');
            $table->string('region');
            $table->bigInteger('region_code');
              $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cdek_regions');
    }
};
