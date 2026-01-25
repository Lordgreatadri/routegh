<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SmsCampaign extends Model
{
    use HasUuid, SoftDeletes;

    protected $table = 'sms_campaigns';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'sms_sender_id',
        'total_recipients',
        'status',
        'sent_at',
        'completed_at',
        'scheduled_at',
        'error_log',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'completed_at' => 'datetime',
        'error_log' => 'array',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'status' => 'pending',
        'total_recipients' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function smsMessages(): HasMany
    {
        return $this->hasMany(SmsMessage::class);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing', 'sent_at' => $this->sent_at ?? now()]);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed', 'completed_at' => now()]);
    }

    public function markAsFailed(): void
    {
        $this->update(['status' => 'failed']);
    }

    public function successfulSendsCount(): int
    {
        return $this->smsMessages()->where('status', 'sent')->count();
    }

    public function failedSendsCount(): int
    {
        return $this->smsMessages()->where('status', 'failed')->count();
    }

    public function deliveredCount(): int
    {
        return $this->smsMessages()->where('status', 'delivered')->count();
    }

    public function getSummary()
    {
        return [
            'total_recipients' => $this->total_recipients,
            'successful_sends' => $this->successfulSendsCount(),
            'failed_sends' => $this->failedSendsCount(),
            'delivered' => $this->deliveredCount(),
            'pending' => $this->smsMessages()->where('status', 'queued')->count(),
        ];
    }


    public function senderId(): BelongsTo
    {
        return $this->belongsTo(SmsSenderId::class, 'sms_sender_id');
    }
}
