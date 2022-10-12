<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('work_id')->nullable();
            $table->string('text')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('reply_to_comment_id')->nullable();
            $table->bigInteger('reply_to_user_id')->nullable();
            $table->bigInteger('parent_comment_id')->nullable();
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
        Schema::dropIfExists('work_comments');
    }
}
