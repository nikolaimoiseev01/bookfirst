<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('title')-> nullable();
            $table->text('col_desc')-> nullable();
            $table->string('cover_2d')-> nullable();;
            $table->string('cover_3d')-> nullable();;
            $table->string('pre_var')-> nullable();;
            $table->bigInteger('col_status_id')->unsigned()-> nullable();
            $table->date('col_date1')-> nullable();
            $table->date('col_date2')-> nullable();
            $table->date('col_date3')-> nullable();
            $table->date('col_date4')-> nullable();
            $table->string('amazon_link')-> nullable();
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
        Schema::dropIfExists('collections');
    }
}
