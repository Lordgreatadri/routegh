<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsApiLog extends Model
{
    protected $table = 'sms_api_logs';

    public $timestamps = false;

    protected $fillable = [
        'sms_message_id',
        'provider',
        'request',
        'response',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function smsMessage(): BelongsTo
    {
        return $this->belongsTo(SmsMessage::class);
    }
}
