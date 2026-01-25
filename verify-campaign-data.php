#!/usr/bin/env php
<?php

// Comprehensive verification of campaign data
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Detailed Campaign Data Verification ===\n\n";

$campaigns = App\Models\SmsCampaign::with(['smsMessages', 'user'])->get();

foreach ($campaigns as $campaign) {
    echo "╔══════════════════════════════════════════════════════════════════╗\n";
    echo "║ CAMPAIGN #{$campaign->id}: {$campaign->title}\n";
    echo "╠══════════════════════════════════════════════════════════════════╣\n";
    echo "║ User: " . ($campaign->user->name ?? 'N/A') . " (ID: {$campaign->user_id})\n";
    echo "║ Status: {$campaign->status}\n";
    echo "║ Total Recipients: {$campaign->total_recipients}\n";
    echo "║ Message: " . substr($campaign->message ?? 'N/A', 0, 60) . "...\n";
    echo "║ \n";
    echo "║ Timestamps:\n";
    echo "║   Created: {$campaign->created_at}\n";
    echo "║   Scheduled: " . ($campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i:s') : 'Instant') . "\n";
    echo "║   Sent: " . ($campaign->sent_at ? $campaign->sent_at->format('Y-m-d H:i:s') : 'Not sent') . "\n";
    echo "║   Completed: " . ($campaign->completed_at ? $campaign->completed_at->format('Y-m-d H:i:s') : 'Not completed') . "\n";
    echo "║ \n";
    
    // Message statistics
    $messages = $campaign->smsMessages;
    $statuses = $messages->groupBy('status')->map->count();
    
    echo "║ Messages ({$messages->count()}):\n";
    foreach ($statuses as $status => $count) {
        echo "║   {$status}: {$count}\n";
    }
    
    // Show sample messages
    echo "║ \n";
    echo "║ Sample Messages:\n";
    foreach ($messages->take(2) as $msg) {
        echo "║   • ID: {$msg->id} | Phone: {$msg->phone} | Status: {$msg->status}\n";
        if ($msg->provider_status) {
            echo "║     Provider: {$msg->provider_status}\n";
        }
        if ($msg->attempts > 0) {
            echo "║     Attempts: {$msg->attempts}\n";
        }
    }
    
    echo "╚══════════════════════════════════════════════════════════════════╝\n\n";
}

// Check API Logs
echo "=== API LOGS ===\n";
$apiLogs = \DB::table('sms_api_logs')->latest('id')->take(5)->get();
if ($apiLogs->count() > 0) {
    echo "Recent API Calls:\n";
    foreach ($apiLogs as $log) {
        echo "  ID: {$log->id} | Message: {$log->sms_message_id} | Provider: {$log->provider}\n";
        echo "    Response: " . substr($log->response, 0, 60) . "\n";
        echo "    Time: {$log->created_at}\n\n";
    }
} else {
    echo "No API logs found.\n";
}

echo "\n=== VERIFICATION COMPLETE ===\n";
