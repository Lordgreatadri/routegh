#!/usr/bin/env php
<?php

// Create a test scheduled campaign
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Creating Test Scheduled Campaign ===\n\n";

// Get first user (should be approved)
$user = App\Models\User::where('status', 'approved')->first();

if (!$user) {
    echo "❌ No approved user found!\n";
    exit(1);
}

echo "✓ Found approved user: {$user->name} (ID: {$user->id})\n";

// Get contacts
$contacts = App\Models\Contact::where('user_id', $user->id)->take(3)->get();

if ($contacts->isEmpty()) {
    echo "❌ No contacts found for this user!\n";
    exit(1);
}

echo "✓ Found {$contacts->count()} contacts\n";

// Create a scheduled campaign (5 minutes from now)
$scheduledTime = now()->addMinutes(5);

$campaign = $user->smsCampaigns()->create([
    'title' => 'Test Scheduled Campaign - ' . now()->format('H:i:s'),
    'message' => 'This is a test scheduled message. Scheduled for: ' . $scheduledTime->format('H:i:s'),
    'total_recipients' => 0,
    'status' => 'pending',
    'scheduled_at' => $scheduledTime,
]);

echo "✓ Created campaign ID: {$campaign->id}\n";
echo "  Title: {$campaign->title}\n";
echo "  Scheduled for: {$scheduledTime->format('Y-m-d H:i:s')}\n";

// Add messages to campaign
foreach ($contacts as $contact) {
    $campaign->smsMessages()->create([
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'phone' => $contact->phone,
        'message' => $campaign->message,
        'status' => 'queued',
    ]);
}

$campaign->update(['total_recipients' => $contacts->count()]);

echo "✓ Added {$contacts->count()} queued messages to campaign\n\n";

echo "--- Campaign Created Successfully ---\n";
echo "Campaign ID: {$campaign->id}\n";
echo "Status: {$campaign->status}\n";
echo "Scheduled At: {$campaign->scheduled_at}\n";
echo "Messages: {$campaign->smsMessages()->count()}\n\n";

echo "To process immediately (for testing), run:\n";
echo "  php artisan campaigns:process-scheduled\n\n";

echo "Or create one for immediate testing (past schedule):\n";

// Create an immediate one (scheduled in the past for testing)
$immediateCampaign = $user->smsCampaigns()->create([
    'title' => 'Test IMMEDIATE Campaign - ' . now()->format('H:i:s'),
    'message' => 'This is an immediate test. Should process right away.',
    'total_recipients' => 0,
    'status' => 'pending',
    'scheduled_at' => now()->subMinute(), // 1 minute ago
]);

foreach ($contacts->take(2) as $contact) {
    $immediateCampaign->smsMessages()->create([
        'user_id' => $user->id,
        'contact_id' => $contact->id,
        'phone' => $contact->phone,
        'message' => $immediateCampaign->message,
        'status' => 'queued',
    ]);
}

$immediateCampaign->update(['total_recipients' => 2]);

echo "\n✓ Also created IMMEDIATE campaign ID: {$immediateCampaign->id}\n";
echo "  This one is scheduled in the past and ready to process NOW\n";
echo "  Run: php artisan campaigns:process-scheduled\n\n";

echo "=== Test Campaigns Created ===\n";
