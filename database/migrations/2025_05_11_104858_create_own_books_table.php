<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('own_books', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('author');
            $table->string('title');
            $table->string('slug');

            $table->foreignId('own_book_status_id')->references('id')->on('own_book_statuses');
            $table->foreignId('own_book_cover_status_id')->nullable()->references('id')->on('own_book_cover_statuses');
            $table->foreignId('own_book_inside_status_id')->nullable()->references('id')->on('own_book_inside_statuses');

            $table->date('deadline_inside')->nullable();
            $table->date('deadline_cover')->nullable();

            $table->bigInteger('pages');
            $table->string('inside_type'); // из системы или фалом
            $table->text('comment')->nullable();
            $table->text('comment_author_inside')->nullable();
            $table->text('comment_author_cover')->nullable();

            $table->bigInteger('internal_promo_type')->nullable();

            $table->bigInteger('price_text_design')->nullable();
            $table->bigInteger('price_text_check')->nullable();
            $table->bigInteger('price_cover')->nullable();
            $table->bigInteger('price_print')->nullable();
            $table->bigInteger('price_promo')->nullable();
            $table->bigInteger('price_total')->nullable();

            $table->dateTime('paid_at_without_print')->nullable();
            $table->dateTime('paid_at_print_only')->nullable();

            $table->string('old_author_email')->nullable();
            $table->text('annotation')->nullable();
            $table->json('selling_links')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('own_books');
    }
};
