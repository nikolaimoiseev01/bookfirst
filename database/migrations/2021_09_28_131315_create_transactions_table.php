<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['CREATED', 'FAILED', 'CONFIRMED'])->default('CREATED');
            $table->bigInteger('user_id');
            $table->string('description', 255)->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payment_method' )->nullable();

            $table->bigInteger('participation_id')->nullable();
            $table->bigInteger('own_book_id')->nullable();
            $table->string('own_book_payment_type')->nullable();

            $table->bigInteger('col_adit_print_needed')->nullable();
            $table->bigInteger('col_adit_print_type')->nullable();
            $table->string('col_adit_send_to_print_address')->nullable();
            $table->string('col_adit_send_to_print_name')->nullable();
            $table->string('col_adit_send_to_print_tel')->nullable();


            $table->bigInteger('bought_collection_id')->nullable();
            $table->bigInteger('bought_own_book_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
