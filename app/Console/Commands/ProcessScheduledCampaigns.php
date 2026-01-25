<?php

namespace App\Console\Commands;

use App\Models\SmsCampaign;
use App\Jobs\ProcessSmsCampaignJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledCampaigns extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled SMS campaigns that are due to be sent';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for scheduled campaigns...');

        // Find campaigns that are:
        // 1. Status is 'pending'
        // 2. Have a scheduled_at time
        // 3. The scheduled_at time has passed (or is now)
        $campaigns = SmsCampaign::where('status', 'pending')
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', now())
            ->get();

        if ($campaigns->isEmpty()) {
            $this->info('No scheduled campaigns found.');
            return 0;
        }

        $this->info("Found {$campaigns->count()} scheduled campaign(s) to process.");

        foreach ($campaigns as $campaign) {
            try {
                $this->info("Processing campaign ID: {$campaign->id} - {$campaign->title}");

                // Update campaign status to processing and set sent_at
                $campaign->update([
                    'status' => 'processing',
                    'sent_at' => now()
                ]);

                // Dispatch the campaign processing job
                ProcessSmsCampaignJob::dispatch($campaign)->onQueue('sms');

                Log::channel('frog')->info('Scheduled campaign dispatched', [
                    'campaign_id' => $campaign->id,
                    'campaign_title' => $campaign->title,
                    'total_recipients' => $campaign->total_recipients,
                    'scheduled_at' => $campaign->scheduled_at,
                    'dispatched_at' => now()
                ]);

                $this->info("✓ Campaign {$campaign->id} dispatched successfully.");
            } catch (\Throwable $e) {
                $this->error("✗ Failed to process campaign {$campaign->id}: {$e->getMessage()}");

                Log::channel('frog')->error('Failed to dispatch scheduled campaign', [
                    'campaign_id' => $campaign->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info('Scheduled campaigns processing completed.');
        return 0;
    }
}
