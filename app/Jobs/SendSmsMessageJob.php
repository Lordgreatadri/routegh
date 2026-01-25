<?php

namespace App\Jobs;

use App\Models\SmsMessage;
use App\Models\SmsApiLog;
use App\Services\SmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendSmsMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $smsMessageId;

    public function __construct(SmsMessage $smsMessage)
    {
        $this->smsMessageId = $smsMessage->id;
        Log::channel('frog')->info('SendSmsMessageJob initialized', ['sms_message' => $smsMessage]);
    }

    public function handle(SmsService $smsService)
    {
        $sms = SmsMessage::find($this->smsMessageId);
        if (! $sms) {
            Log::channel('frog')->warning('SMS message not found, sms Job', ['sms_message_id' => $this->smsMessageId]);
            return;
        }

        try {
            Log::channel('frog')->info('Processing SMS message, sms Job', [
                'sms_message_id' => $sms->id,
                'phone' => $sms->phone,
                'sms_sender_id' => $sms->smsSender->sender_id ?? null,
                'campaign_id' => $sms->sms_campaign_id
            ]);

            $result = $smsService->sendMessageWithSender($sms->phone, $sms->message, $sms->smsSender->sender_id);

            // Record API log
            SmsApiLog::create([
                'sms_message_id' => $sms->id,
                'provider' => config('services.sms.driver', 'log'),
                'request' => json_encode([
                    'phone' => $sms->phone,
                    'message_length' => strlen($sms->message)
                ]),
                'response' => is_string($result['raw']) ? $result['raw'] : json_encode($result['raw']),
            ]);

            if ($result['ok']) {
                $providerResponse = is_string($result['raw']) ? $result['raw'] : json_encode($result['raw']);
                $sms->markAsSent(
                    $result['provider_message_id'] ?? null, 
                    $result['provider_status'] ?? null,
                    $providerResponse
                );
                
                // Increment system metrics for messages sent today
                \App\Models\SystemMetric::incrementMessagesSent();
                
                Log::channel('frog')->info('SMS sent successfully', [
                    'sms_message_id' => $sms->id,
                    'phone' => $sms->phone,
                    'sender_id' => $sms->smsSender->sender_id ?? null,
                    'provider_status' => $result['provider_status']
                ]);
            } else {
                $sms->update(['attempts' => 1]);
                $providerResponse = is_string($result['raw']) ? $result['raw'] : json_encode($result['raw']);
                $sms->update(['provider_response' => $providerResponse]);
                $sms->markAsFailed($result['raw'] ?? 'send failed');
                
                Log::channel('frog')->error('SMS send failed', [
                    'sms_message_id' => $sms->id,
                    'phone' => $sms->phone,
                    'sender_id' => $sms->smsSender->sender_id ?? null,
                    'attempts' => 1,
                    'error' => $result['raw']
                ]);
            }

            // After each message processed, check whether the campaign has any remaining queued messages
            try {
                $campaign = $sms->smsCampaign;
                if ($campaign) {
                    $remaining = $campaign->smsMessages()->where('status', 'queued')->count();
                    if ($remaining === 0) {
                        $campaign->markAsCompleted();
                        
                        Log::channel('frog')->info('Campaign completed', [
                            'campaign_id' => $campaign->id,
                            'campaign_title' => $campaign->title,
                            'sms_sender_id' => $campaign->senderId->sender_id ?? null,
                            'total_recipients' => $campaign->total_recipients,
                            'successful' => $campaign->successfulSendsCount(),
                            'failed' => $campaign->failedSendsCount()
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::channel('frog')->warning('Failed to update campaign completion status', [
                    'error' => $e->getMessage(),
                    'sms_message_id' => $sms->id,
                    'campaign_id' => $sms->sms_campaign_id,
                    'sender_id' => $sms->smsSender->sender_id ?? null,
                ]);
            }
        } catch (\Throwable $e) {
            Log::channel('frog')->error('SendSmsMessageJob exception', [
                'sms_message_id' => $sms->id,
                'phone' => $sms->phone,
                'sms_sender_id' => $sms->smsSender->sender_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            $sms->increment('attempts');
            $sms->markAsFailed($e->getMessage());
        }
    }
}
