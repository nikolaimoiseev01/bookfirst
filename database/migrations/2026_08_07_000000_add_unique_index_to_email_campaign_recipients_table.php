<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('
                DELETE FROM email_campaign_recipients AS duplicate
                USING email_campaign_recipients AS original
                WHERE duplicate.email_campaign_id = original.email_campaign_id
                    AND duplicate.email_recipient_id = original.email_recipient_id
                    AND duplicate.id > original.id
            ');
        } else {
            DB::statement('
                DELETE duplicate FROM email_campaign_recipients AS duplicate
                INNER JOIN email_campaign_recipients AS original
                    ON duplicate.email_campaign_id = original.email_campaign_id
                    AND duplicate.email_recipient_id = original.email_recipient_id
                    AND duplicate.id > original.id
            ');
        }

        Schema::table('email_campaign_recipients', function (Blueprint $table) {
            $table->unique(['email_campaign_id', 'email_recipient_id'], 'campaign_recipient_unique');
        });
    }

    public function down(): void
    {
        Schema::table('email_campaign_recipients', function (Blueprint $table) {
            $table->dropUnique('campaign_recipient_unique');
        });
    }
};
