<?php

namespace App\Jobs;

use App\Models\SmsCampaign;
use App\Models\Contact;
use App\Models\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessSmsCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaignId;

    public function __construct(SmsCampaign $campaign)
    {
        $this->campaignId = $campaign->id;
        Log::channel('frog')->info('Starting processing for campaign, campaign Job', ['campaign' => $this->campaignId]);
    }

    public function handle()
    {
        $campaign = SmsCampaign::find($this->campaignId);

        if (!$campaign) {
            Log::channel('frog')->error('Campaign not found campaign Job', ['campaign_id' => $this->campaignId]);
            return;
        }

        // Mark campaign as processing
        $campaign->markAsProcessing();

        Log::channel('frog')->info('Processing campaign campaign Job', [
            'campaign_id' => $campaign->id,
            'campaign_title' => $campaign->title,
            'sms_sender_id' => $campaign->senderId->sender_id ?? null,
            'total_recipients' => $campaign->total_recipients
        ]);

        // Get the target contacts based on metadata
        $metadata = $campaign->metadata ?? [];
        $groupId = $metadata['contact_group_id'] ?? null;

        $contacts = collect();
        
        if ($groupId) {
            // Get contacts from the specific group
            $group = \App\Models\ContactGroup::where('id', $groupId)->first();
            if ($group) {
                $contacts = $group->contacts;
            }
        } else {
            // Get all contacts for the user
            $contacts = Contact::where('user_id', $campaign->user_id)->get();
        }

        if ($contacts->isEmpty()) {
            Log::channel('frog')->warning('No contacts found for campaign', [
                'campaign_id' => $campaign->id,
                'sender_id' => $campaign->senderId->sender_id ?? null,
                'group_id' => $groupId
            ]);
            
            $campaign->update([
                'status' => 'completed',
                'completed_at' => now(),
                'error_log' => ['No contacts found to send messages to']
            ]);
            return;
        }

        $messagesCreated = 0;
        $errors = [];
        
        Log::channel('frog')->info('Creating SMS messages for campaign job', [
            'campaign_id' => $campaign->id,
            'total_contacts' => $contacts->count(),
            'sms_sender_id' => $campaign->senderId->sender_id ?? null,
        ]);

        // Create SMS messages for each contact
        foreach ($contacts as $contact) {
            try {
                // Check if message already exists for this campaign and contact
                $existing = SmsMessage::where('sms_campaign_id', $campaign->id)
                    ->where('contact_id', $contact->id)
                    ->first();

                if ($existing) {
                    continue; // Skip if already created
                }

                $smsMessage = SmsMessage::create([
                    'user_id' => $campaign->user_id,
                    'sms_sender_id' => $campaign->sms_sender_id,
                    'sms_campaign_id' => $campaign->id,
                    'contact_id' => $contact->id,
                    'phone' => $contact->phone,
                    'message' => $campaign->message,
                    'status' => 'queued',
                ]);

                $messagesCreated++;

                // Dispatch send job for this message
                SendSmsMessageJob::dispatch($smsMessage);

            } catch (\Throwable $e) {
                $errors[] = "Failed to create message for contact {$contact->id}: " . $e->getMessage();
                Log::channel('frog')->error('Failed to create SMS message', [
                    'campaign_id' => $campaign->id,
                    'contact_id' => $contact->id,
                    'sms_sender_id' => $campaign->senderId->sender_id ?? null,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::channel('frog')->info('Campaign messages created and dispatched', [
            'campaign_id' => $campaign->id,
            'messages_created' => $messagesCreated,
            'sms_sender_id' => $campaign->senderId->sender_id ?? null,
            'errors_count' => count($errors)
        ]);

        // Update total recipients count
        $campaign->update(['total_recipients' => $messagesCreated]);
        
        // If there were errors, log them to the campaign
        if (!empty($errors)) {
            $campaign->update([
                'error_log' => $errors
            ]);
        }

        // If no messages were created, mark as completed
        if ($messagesCreated === 0) {
            $campaign->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        }
    }
}
