<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'status',
        'scheduled_at',
        'completed_at',
        'message_template',
        'total_recipients',
        'successful_sends',
        'failed_sends',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $attributes = [
        'status' => 'draft',
        'total_recipients' => 0,
        'successful_sends' => 0,
        'failed_sends' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function incrementSuccessfulSends(): void
    {
        $this->increment('successful_sends');
        $this->updateStatusIfComplete();
    }

    public function incrementFailedSends(): void
    {
        $this->increment('failed_sends');
        $this->updateStatusIfComplete();
    }

    protected function updateStatusIfComplete(): void
    {
        $totalProcessed = $this->successful_sends + $this->failed_sends;
        if ($totalProcessed >= $this->total_recipients) {
            $this->update([
                'status' => 'sent',
                'completed_at' => now(),
            ]);
        }
    }
}
