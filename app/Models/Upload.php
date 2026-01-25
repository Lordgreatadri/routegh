<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Upload extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'file_type',
        'total_rows',
        'processed_rows',
        'failed_rows',
        'status',
        'error_log',
    ];

    protected $casts = [
        'error_log' => 'array',
    ];

    protected $attributes = [
        'status' => 'pending',
        'total_rows' => 0,
        'processed_rows' => 0,
        'failed_rows' => 0,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsFailed(array $errors = []): void
    {
        $this->update([
            'status' => 'failed',
            'error_log' => $errors,
        ]);
    }

    public function addError(string $error): void
    {
        $errors = $this->error_log ?? [];
        $errors[] = $error;
        $this->update(['error_log' => $errors]);
    }

    public function incrementProcessedRows(): void
    {
        $this->increment('processed_rows');
    }

    public function incrementFailedRows(): void
    {
        $this->increment('failed_rows');
    }
}
