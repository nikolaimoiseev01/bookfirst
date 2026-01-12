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
        Schema::table('almost_complete_actions', function (Blueprint $table) {
            $table->dropForeign('almost_complete_actions_almost_complete_action_type_id_foreign');
        });
        Schema::dropIfExists('almost_complete_action_types');
        Schema::table('almost_complete_actions', function (Blueprint $table) {

            $table->dropColumn(['model_type', 'model_id']);
            $table->renameColumn('almost_complete_action_type_id', 'type');
            $table->boolean('is_unsubscribed')->default(false);
            $table->string('type')->change();
            $table->json('data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('almost_complete_actions', function (Blueprint $table) {
            //
        });
    }
};
