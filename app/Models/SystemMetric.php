<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemMetric extends Model
{
    protected $table = 'system_metrics';

    protected $fillable = [
        'date',
        'total_messages_sent',
        'total_recipients',
        'total_uploads',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public static function recordMetrics(): void
    {
        $today = now()->toDateString();
        
        $metric = self::firstOrCreate(
            ['date' => $today],
            [
                'total_messages_sent' => 0,
                'total_recipients' => 0,
                'total_uploads' => 0,
            ]
        );

        $metric->update([
            'total_messages_sent' => SmsMessage::whereDate('sent_at', $today)->count(),
            'total_uploads' => Upload::whereDate('created_at', $today)->count(),
        ]);
    }

    public static function incrementMessagesSent(): void
    {
        $today = now()->toDateString();
        
        $metric = self::firstOrCreate(
            ['date' => $today],
            [
                'total_messages_sent' => 0,
                'total_recipients' => 0,
                'total_uploads' => 0,
            ]
        );

        $metric->increment('total_messages_sent');
    }

    public static function incrementUploads(int $recipientsCount = 0): void
    {
        $today = now()->toDateString();
        
        $metric = self::firstOrCreate(
            ['date' => $today],
            [
                'total_messages_sent' => 0,
                'total_recipients' => 0,
                'total_uploads' => 0,
            ]
        );

        $metric->increment('total_uploads');
        if ($recipientsCount > 0) {
            $metric->increment('total_recipients', $recipientsCount);
        }
    }
}
