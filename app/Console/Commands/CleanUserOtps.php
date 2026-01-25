<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserOtp;
use Carbon\Carbon;

class CleanUserOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userotps:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired user_otps rows from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now();
        $deleted = UserOtp::whereNotNull('expired_at')
            ->where('expired_at', '<', $now)
            ->delete();

        $this->info("Deleted {$deleted} expired user_otps rows.");

        return self::SUCCESS;
    }
}
