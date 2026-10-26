<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ai_assistant_conversations', function (Blueprint $table) {
            $table->string('status')->default('completed')->after('generation_type');
            $table->text('error_message')->nullable()->after('answer');
            $table->timestamp('started_at')->nullable()->after('image_path');
            $table->timestamp('completed_at')->nullable()->after('started_at');
        });
    }

    public function down(): void
    {
        Schema::table('ai_assistant_conversations', function (Blueprint $table) {
            $table->dropColumn(['status', 'error_message', 'started_at', 'completed_at']);
        });
    }
};
