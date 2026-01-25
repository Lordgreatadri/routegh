<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'user_id',
        'upload_id',
        'contact_group_id',
        'name',
        'phone',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upload(): BelongsTo
    {
        return $this->belongsTo(Upload::class);
    }

    public function contactGroup(): BelongsTo
    {
        return $this->belongsTo(ContactGroup::class);
    }

    public static function isValidPhoneNumber(string $phone): bool
    {
        // Basic validation: phone should be numeric and at least 10 digits
        $phone = preg_replace('/\D/', '', $phone);
        return strlen($phone) >= 10 && is_numeric($phone);
    }

    public function formatPhoneNumber(): string
    {
        return preg_replace('/\D/', '', $this->phone);
    }
}
