<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('
            DELETE t1 FROM email_campaign_recipients t1
            INNER JOIN email_campaign_recipients t2
                ON t1.email_campaign_id = t2.email_campaign_id
                AND t1.email_recipient_id = t2.email_recipient_id
                AND t1.id > t2.id
        ');

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
