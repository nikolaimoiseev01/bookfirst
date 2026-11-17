<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('printing_companies', function (Blueprint $table) {
            $table->string('pickup_city_code')->nullable();
            $table->text('pickup_address')->nullable();
            $table->string('pickup_contact_name')->nullable();
            $table->string('pickup_contact_phone')->nullable();
        });

        Schema::table('cdek_pickup_groups', function (Blueprint $table) {
            $table->foreignId('printing_company_id')->nullable()->constrained('printing_companies')->nullOnDelete();
            $table->string('printing_company_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cdek_pickup_groups', function (Blueprint $table) {
            $table->dropConstrainedForeignId('printing_company_id');
            $table->dropColumn('printing_company_name');
        });

        Schema::table('printing_companies', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_city_code',
                'pickup_address',
                'pickup_contact_name',
                'pickup_contact_phone',
            ]);
        });
    }
};
