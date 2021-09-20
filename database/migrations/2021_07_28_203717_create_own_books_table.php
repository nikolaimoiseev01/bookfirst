<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOwnBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('own_books', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('author');
            $table->string('title');
            $table->bigInteger('own_book_status_id');
            $table->string('inside_file');
            $table->string('cover_2d')->nullable();;
            $table->string('cover_3d')->nullable();
            $table->bigInteger('pages');
            $table->bigInteger('color_pages');
            $table->string('inside_type');
            $table->string('cover_comment')->nullable();
            $table->bigInteger('promo_type')->nullable();


            $table->bigInteger('text_design_price')->nullable();
            $table->bigInteger('text_check_price')->nullable();
            $table->bigInteger('cover_price')->nullable();
            $table->bigInteger('print_price')->nullable();
            $table->bigInteger('inside_price')->nullable();
            $table->bigInteger('promo_price')->nullable();
            $table->bigInteger('total_price')->nullable();

            $table->string('old_author_email')->nullable();
            $table->string('own_book_desc')->nullable();
            $table->string('amazon_link')->nullable();



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
        Schema::dropIfExists('own_books');
    }
}
