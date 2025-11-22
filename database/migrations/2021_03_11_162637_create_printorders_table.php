<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printorders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('participation_id')-> nullable();
            $table->bigInteger('collection_id')-> nullable();
            $table->bigInteger('user_id')-> nullable();
            $table->bigInteger('books_needed')-> nullable();
            $table->string('send_to_name')-> nullable();
            $table->string('send_to_surname')-> nullable();
            $table->string('send_to_tel')-> nullable();
            $table->string('send_to_country')-> nullable();
            $table->string('send_to_city')-> nullable();
            $table->string('send_to_address')-> nullable();
            $table->timestamp('paid_at')-> nullable();
            $table->bigInteger('send_to_index')-> nullable();
            $table->bigInteger('track_number')-> nullable();
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
        Schema::dropIfExists('printorders');
    }
}
