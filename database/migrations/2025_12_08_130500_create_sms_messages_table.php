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
        Schema::create('sms_messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('sms_campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sms_sender_id')->constrained('sms_sender_ids')->onDelete('cascade');
            $table->foreignId('contact_id')->constrained()->onDelete('cascade');
            $table->string('phone', 20)->nullable()->comment('Snapshotted phone number');
            $table->text('message')->comment('Snapshotted message body');
            $table->enum('status', ['queued', 'sent', 'failed', 'delivered', 'undelivered'])->default('queued');
            $table->string('provider_message_id')->nullable()->comment('Returned by SMS API');
            $table->string('provider_status')->nullable()->comment('Raw response');
            $table->integer('retry_count')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index('sms_campaign_id');
            $table->index('user_id');
            $table->index('contact_id');
            $table->index('sms_sender_id');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_messages');
    }
};
