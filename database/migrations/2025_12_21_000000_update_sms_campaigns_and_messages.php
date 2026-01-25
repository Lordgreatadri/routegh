<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sms_campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('sms_campaigns', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('scheduled_at');
            }
            if (!Schema::hasColumn('sms_campaigns', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('sent_at');
            }
            if (!Schema::hasColumn('sms_campaigns', 'error_log')) {
                $table->json('error_log')->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('sms_campaigns', 'metadata')) {
                $table->json('metadata')->nullable()->after('error_log');
            }
        });

        Schema::table('sms_messages', function (Blueprint $table) {
            if (!Schema::hasColumn('sms_messages', 'error_message')) {
                $table->text('error_message')->nullable()->after('provider_status');
            }
            if (!Schema::hasColumn('sms_messages', 'provider_response')) {
                $table->text('provider_response')->nullable()->after('error_message');
            }
            if (!Schema::hasColumn('sms_messages', 'attempts')) {
                $table->integer('attempts')->default(0)->after('provider_response');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sms_campaigns', function (Blueprint $table) {
            $table->dropColumn(['sent_at', 'completed_at', 'error_log', 'metadata']);
        });

        Schema::table('sms_messages', function (Blueprint $table) {
            $table->dropColumn(['error_message', 'provider_response', 'attempts']);
        });
    }
};
