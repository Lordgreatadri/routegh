<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SmsMessage extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'sms_messages';

    protected $fillable = [
        'sms_campaign_id',
        'user_id',
        'contact_id',
        'sms_sender_id',
        'phone',
        'message',
        'status',
        'provider_message_id',
        'provider_status',
        'error_message',
        'provider_response',
        'attempts',
        'retry_count',
        'sent_at',
        'delivered_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'queued',
        'retry_count' => 0,
        'attempts' => 0,
    ];


    public function smsSender(): BelongsTo
    {
        return $this->belongsTo(SmsSenderId::class, 'sms_sender_id');
    }

    public function smsCampaign(): BelongsTo
    {
        return $this->belongsTo(SmsCampaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function smsApiLog(): HasOne
    {
        return $this->hasOne(SmsApiLog::class);
    }

    public function markAsQueued(): void
    {
        $this->update(['status' => 'queued']);
    }

    public function markAsSent(string $providerMessageId = null, string $providerStatus = null, string $providerResponse = null): void
    {
        $this->update([
            'status' => 'sent',
            'provider_message_id' => $providerMessageId ?? $this->provider_message_id,
            'provider_status' => $providerStatus,
            'provider_response' => $providerResponse,
            'attempts' => 1,
            'sent_at' => $this->sent_at ?? now(),
            // 'delivered_at' => now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function markAsFailed(string $providerStatus = null): void
    {
        $this->update([
            'status' => 'failed',
            'provider_status' => $providerStatus,
        ]);
    }

    public function markAsUndelivered(): void
    {
        $this->update(['status' => 'undelivered']);
    }

    public function incrementRetryCount(): void
    {
        $this->increment('retry_count');
    }

    public function canRetry(int $maxRetries = 3): bool
    {
        return $this->retry_count < $maxRetries && $this->status === 'failed';
    }
}
