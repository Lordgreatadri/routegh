#!/usr/bin/env php
<?php

// Test script to verify SMS campaign system
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== SMS Campaign System Test ===\n\n";

// 1. Check Users
$usersCount = App\Models\User::count();
$approvedUsers = App\Models\User::where('status', 'approved')->count();
echo "✓ Users: $usersCount (Approved: $approvedUsers)\n";

// 2. Check Contacts
$contactsCount = App\Models\Contact::count();
echo "✓ Contacts: $contactsCount\n";

// 3. Check Contact Groups
$groupsCount = App\Models\ContactGroup::count();
echo "✓ Contact Groups: $groupsCount\n";

// 4. Check Campaigns
$campaigns = App\Models\SmsCampaign::get();
echo "✓ Campaigns: {$campaigns->count()}\n\n";

if ($campaigns->count() > 0) {
    echo "--- Campaign Details ---\n";
    foreach ($campaigns as $campaign) {
        echo "ID: {$campaign->id}\n";
        echo "  Title: {$campaign->title}\n";
        echo "  Status: {$campaign->status}\n";
        echo "  Recipients: {$campaign->total_recipients}\n";
        echo "  Scheduled: " . ($campaign->scheduled_at ? $campaign->scheduled_at->format('Y-m-d H:i:s') : 'Instant') . "\n";
        echo "  Messages: " . $campaign->smsMessages()->count() . "\n";
        
        $statuses = $campaign->smsMessages()
            ->select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');
        
        echo "  Message Status: " . json_encode($statuses) . "\n\n";
    }
}

// 5. Test Scheduled Campaign Finder
echo "--- Testing Scheduled Campaign Finder ---\n";
$scheduled = App\Models\SmsCampaign::where('status', 'pending')
    ->whereNotNull('scheduled_at')
    ->where('scheduled_at', '<=', now())
    ->get();

echo "Campaigns ready to process: {$scheduled->count()}\n";

// 6. Check Queue Jobs
echo "\n--- Queue Status ---\n";
$pendingJobs = \DB::table('jobs')->count();
$failedJobs = \DB::table('failed_jobs')->count();
echo "Pending Jobs: $pendingJobs\n";
echo "Failed Jobs: $failedJobs\n";

// 7. Check Configuration
echo "\n--- Configuration ---\n";
echo "SMS Driver: " . config('services.sms.driver') . "\n";
echo "Queue Connection: " . config('queue.default') . "\n";
echo "FrogSMS Configured: " . (config('services.frogsms.username') ? 'Yes' : 'No') . "\n";

echo "\n=== Test Complete ===\n";
