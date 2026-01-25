<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasUuid;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'recipient_number',
        'message_content',
        'status',
        'message_id',
        'sent_at',
        'delivered_at',
        'error_message',
        'metadata',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'delivered_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function markAsSending(string $messageId = null): void
    {
        $this->update([
            'status' => 'sending',
            'message_id' => $messageId,
            'sent_at' => now(),
        ]);
    }

    public function markAsSent(string $messageId = null): void
    {
        $this->update([
            'status' => 'sent',
            'message_id' => $messageId ?? $this->message_id,
            'sent_at' => $this->sent_at ?? now(),
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function markAsFailed(string $error = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
        ]);
    }
}
