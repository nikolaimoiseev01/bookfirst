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
        Schema::table('inner_tasks', function (Blueprint $table) {
           $table->foreignId('inner_task_type_id');
           $table->boolean('flg_finished')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inner_tasks', function (Blueprint $table) {
            //
        });
    }
};
