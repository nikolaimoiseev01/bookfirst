<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviewCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preview_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('collection_id')->nullable();
            $table->bigInteger('participation_id')->nullable();
            $table->bigInteger('own_book_id')->nullable();
            $table->string('own_book_comment_type')->nullable();
            $table->bigInteger('page');
            $table->text('text');
            $table->bigInteger('status_done');
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
        Schema::dropIfExists('preview_comments');
    }
}
