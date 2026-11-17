<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cdek_shipments', function (Blueprint $table) {
            $table->string('shipment_type')->default('own_book');
            $table->json('collection_ids')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cdek_shipments', function (Blueprint $table) {
            $table->dropColumn(['shipment_type', 'collection_ids']);
        });
    }
};
