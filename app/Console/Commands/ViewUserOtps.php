<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\UserOtp;

class ViewUserOtps extends Command
{
    protected $signature = 'userotps:recent {--from-log : Read OTPs from laravel.log instead of DB} {--limit=50 : Number of log lines to scan or DB rows to show}';

    protected $description = '(dev) Show recent OTPs either from DB (hashed) or from laravel.log (plaintext when using log driver)';

    public function handle(): int
    {
        if (! in_array(config('app.env'), ['local', 'development']) && !config('app.debug')) {
            $this->error('This command is for local/dev environments only.');
            return self::FAILURE;
        }

        $limit = (int) $this->option('limit');

        if ($this->option('from-log')) {
            $logPath = storage_path('logs/laravel.log');
            if (! File::exists($logPath)) {
                $this->error('Log file not found: ' . $logPath);
                return self::FAILURE;
            }

            $lines = array_slice(array_filter(explode("\n", File::get($logPath))), -$limit);
            $matches = [];
            foreach ($lines as $line) {
                // Expecting lines like: [SMS][OTP] to {phone}: {otp}
                if (preg_match('/\[SMS\]\[OTP\] to\s+([0-9+\-\s]+):\s*(\d{4,8})/i', $line, $m)) {
                    $matches[] = ['phone' => trim($m[1]), 'otp' => trim($m[2]), 'line' => $line];
                }
            }

            if (empty($matches)) {
                $this->info('No OTP entries found in the last ' . $limit . ' log lines.');
                return self::SUCCESS;
            }

            $this->table(['Phone','OTP','Sample Log'], array_map(function($m){ return [$m['phone'], $m['otp'], $m['line']]; }, $matches));
            return self::SUCCESS;
        }

        $rows = UserOtp::orderByDesc('created_at')->limit($limit)->get(['phone_number','expired_at','created_at']);
        if ($rows->isEmpty()) {
            $this->info('No user_otps rows found.');
            return self::SUCCESS;
        }

        $this->table(['Phone','Created At','Expired At'], $rows->map(function($r){ return [$r->phone_number, $r->created_at, $r->expired_at]; })->toArray());

        return self::SUCCESS;
    }
}
