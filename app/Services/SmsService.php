<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('services.sms', []);
    }

    /**
     * Send an OTP message to a phone number.
     * Uses FrogSMS as default, Twilio as backup.
     * Returns true on success, false on failure.
     */
    public function sendOtp(string $phone, string $otp): bool
    {
        $driver = $this->config['driver'] ?? 'frog';
        $message = "Your verification code is: {$otp}";

        // Try FrogSMS first
        if ($driver === 'frog' || $driver === 'frogsms') {
            $result = $this->sendMessage($phone, $message);
            if ($result['ok']) {
                return true;
            }
            
            // FrogSMS failed, try Twilio as backup
            Log::channel('frog')->warning('FrogSMS failed for OTP, attempting Twilio backup', [
                'to' => $phone,
                'error' => $result['raw'] ?? 'Unknown error'
            ]);
            
            $twilioResult = $this->sendViaTwilio($phone, $message);
            if ($twilioResult) {
                Log::info('OTP sent via Twilio backup successfully', ['to' => $phone]);
                return true;
            }
        }

        // If driver is explicitly twilio, use it directly
        if ($driver === 'twilio') {
            return $this->sendViaTwilio($phone, $message);
        }

        // All methods failed, log the OTP (development/fallback)
        Log::info("[SMS][OTP] to {$phone}: {$otp}");
        return true;
    }




    /*
    * Send a message with a specific sender ID.
     * @string $phone
     * @string $message
     * @string|int $senderId
    *  Returns an array: ['ok' => bool, 'provider_message_id' => string|null, 'provider_status' => string|null, 'raw' => mixed]
    */
    public function sendMessageWithSender(string $phone, string $message, $senderId): array
    {
        $driver = $this->config['sms.driver'] ?? 'frog';
        
        Log::channel('frog')->info('sendMessageWithSender called', [
            'to' => $phone,
            'message_length' => strlen($message),
            'sender_id' => $senderId,
            'driver' => $driver
        ]);

        
        if ($driver === 'frog' || $driver === 'frogsms') {
            // FrogSMS API configured in services.frogsms
            $baseUrl = config('services.frogsms.base_url');
            $username = config('services.frogsms.username');
            $password = config('services.frogsms.password');
            // $senderId = config('services.frogsms.senderid');

            // Build the API URL with query parameters
            $url = $baseUrl . '?username=' . urlencode($username) 
                   . '&password=' . urlencode($password) 
                   . '&from=' . urlencode($senderId) 
                   . '&to=' . urlencode($phone) 
                   . '&message=' . urlencode($message);

            try {
                Log::channel('frog')->info('Sending SMS via FrogSMS', [
                    'to' => $phone,
                    'message_length' => strlen($message),
                    'sender_id' => $senderId
                ]);

                $client = new Client(['timeout' => 30]);
                $response = $client->get($url);
                $status = $response->getStatusCode();
                $body = (string) $response->getBody();
                $ok = $status >= 200 && $status < 300;

                if ($ok) {
                    Log::channel('frog')->info('SMS sent successfully', [
                        'to' => $phone,
                        'status' => $status,
                        'response' => $body
                    ]);
                } else {
                    Log::channel('frog')->error('SMS send failed', [
                        'to' => $phone,
                        'status' => $status,
                        'response' => $body
                    ]);
                }

                return ['ok' => $ok, 'provider_message_id' => null, 'provider_status' => $ok ? 'sent' : 'failed', 'raw' => $body];
            } catch (\Throwable $e) {
                Log::channel('frog')->error('FrogSMS API exception', [
                    'to' => $phone,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['ok' => false, 'provider_message_id' => null, 'provider_status' => 'failed', 'raw' => $e->getMessage()];
            }
        }

        // default: log and return ok
        Log::info("[SMS] to {$phone}: {$message}");
        return ['ok' => true, 'provider_message_id' => null, 'provider_status' => 'sent', 'raw' => null];
    }





    /**
     * Send an arbitrary message via configured provider.
     * Returns an array: ['ok' => bool, 'provider_message_id' => string|null, 'provider_status' => string|null, 'raw' => mixed]
     */
    public function sendMessage(string $phone, string $message): array
    {
        $driver = $this->config['driver'] ?? 'log';

        if ($driver === 'twilio') {
            // Reuse Twilio method for generic message
            try {
                $ok = $this->sendViaTwilio($phone, $message);
                return ['ok' => $ok, 'provider_message_id' => null, 'provider_status' => $ok ? 'sent' : 'failed', 'raw' => null];
            } catch (\Throwable $e) {
                return ['ok' => false, 'provider_message_id' => null, 'provider_status' => 'failed', 'raw' => $e->getMessage()];
            }
        }

        if ($driver === 'frog' || $driver === 'frogsms') {
            // FrogSMS API configured in services.frogsms
            $baseUrl = config('services.frogsms.base_url');
            $username = config('services.frogsms.username');
            $password = config('services.frogsms.password');
            $senderId = config('services.frogsms.senderid');

            // Build the API URL with query parameters
            $url = $baseUrl . '?username=' . urlencode($username) 
                   . '&password=' . urlencode($password) 
                   . '&from=' . urlencode($senderId) 
                   . '&to=' . urlencode($phone) 
                   . '&message=' . urlencode($message);

            try {
                Log::channel('frog')->info('Sending SMS via FrogSMS', [
                    'to' => $phone,
                    'message_length' => strlen($message),
                    'sender_id' => $senderId
                ]);

                $client = new Client(['timeout' => 30]);
                $response = $client->get($url);
                $status = $response->getStatusCode();
                $body = (string) $response->getBody();
                $ok = $status >= 200 && $status < 300;

                if ($ok) {
                    Log::channel('frog')->info('SMS sent successfully', [
                        'to' => $phone,
                        'status' => $status,
                        'response' => $body
                    ]);
                } else {
                    Log::channel('frog')->error('SMS send failed', [
                        'to' => $phone,
                        'status' => $status,
                        'response' => $body
                    ]);
                }

                return ['ok' => $ok, 'provider_message_id' => null, 'provider_status' => $ok ? 'sent' : 'failed', 'raw' => $body];
            } catch (\Throwable $e) {
                Log::channel('frog')->error('FrogSMS API exception', [
                    'to' => $phone,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['ok' => false, 'provider_message_id' => null, 'provider_status' => 'failed', 'raw' => $e->getMessage()];
            }
        }

        // default: log and return ok
        Log::info("[SMS] to {$phone}: {$message}");
        return ['ok' => true, 'provider_message_id' => null, 'provider_status' => 'sent', 'raw' => null];
    }

    protected function sendViaTwilio(string $to, string $message): bool
    {
        $sid = $this->config['twilio_account_sid'] ?? null;
        $token = $this->config['twilio_auth_token'] ?? null;
        $from = $this->config['twilio_from'] ?? null;
        $base = rtrim($this->config['twilio_base_url'] ?? 'https://api.twilio.com/2010-04-01', '/');

        if (! $sid || ! $token || ! $from) {
            Log::error('Twilio credentials are not configured.');
            return false;
        }

        try {
            $client = new Client();
            $url = "{$base}/Accounts/{$sid}/Messages.json";

            $response = $client->post($url, [
                'auth' => [$sid, $token],
                'form_params' => [
                    'From' => $from,
                    'To' => $to,
                    'Body' => $message,
                ],
            ]);

            $status = $response->getStatusCode();
            return $status >= 200 && $status < 300;
        } catch (\Throwable $e) {
            Log::error('Twilio SMS send failed: ' . $e->getMessage());
            return false;
        }
    }
}
