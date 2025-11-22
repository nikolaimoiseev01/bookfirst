<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Participations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('collection_id')-> nullable();
            $table->bigInteger('user_id')-> nullable();
            $table->string('name')-> nullable();
            $table->string('surname')-> nullable();
            $table->string('nickname')-> nullable();
            $table->bigInteger('works_number')-> nullable();
            $table->bigInteger('rows')-> nullable();
            $table->bigInteger('pages')-> nullable();
            $table->bigInteger('pat_status_id')-> nullable();
            $table->string('promocode')-> nullable();
            $table->bigInteger('part_price')-> nullable();
            $table->bigInteger('print_price')-> nullable();
            $table->bigInteger('check_price')-> nullable();
            $table->bigInteger('send_price')-> nullable();
            $table->bigInteger('total_price')-> nullable();
            $table->text('file')-> nullable();
            $table->bigInteger('printorder_id')-> nullable();
            $table->bigInteger('chat_id')-> nullable();
            $table->dateTime('approved_at')->nullable();
            $table->dateTime('paid_at')->nullable();

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
        Schema::dropIfExists('Participations');
    }
}
