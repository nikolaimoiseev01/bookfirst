<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cdek_shipments', function (Blueprint $table) {
            $table->foreignId('own_book_id')->nullable()->after('created_by')->constrained('own_books')->nullOnDelete();
            $table->unsignedBigInteger('print_order_id')->nullable()->after('own_book_id')->index();
            $table->string('book_title')->nullable()->after('print_order_id');
            $table->text('recipient_address')->nullable()->after('recipient_phone');
            $table->string('address_type')->nullable()->after('recipient_address');
            $table->string('delivery_point')->nullable()->after('address_type');
        });
    }

    public function down(): void
    {
        Schema::table('cdek_shipments', function (Blueprint $table) {
            $table->dropForeign(['own_book_id']);
            $table->dropIndex(['print_order_id']);
            $table->dropColumn(['own_book_id', 'print_order_id', 'book_title', 'recipient_address', 'address_type', 'delivery_point']);
        });
    }
};
