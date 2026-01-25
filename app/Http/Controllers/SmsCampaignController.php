<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\View\View;
use App\Models\SmsCampaign;
use App\Models\SmsSenderId;
use Illuminate\Http\Request;
use App\Jobs\ProcessSmsCampaignJob;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreSmsCampaignRequest;
use App\Http\Requests\UpdateSmsCampaignRequest;

class SmsCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified', 'approved']);
    }

    /**
     * Display a listing of the campaigns.
     */
    public function index(Request $request): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        $perPage = $request->query('per_page', 15);

        $campaigns = auth()->user()->smsCampaigns()
            ->latest()
            ->paginate($perPage);

        return view('users.campaigns.index', ['campaigns' => $campaigns]);
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        abort_if(!auth()->user()->isApproved(), 403);

        // Prefer loading contact groups for large contact sets. Manual contacts
        // can still be provided via the form.
        $contactGroups = auth()->user()->contactGroups()->pluck('name', 'id');

        // Get only approved and active sender IDs for the user
        $senderIds = SmsSenderId::where('user_id', auth()->id())
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->pluck('sender_id', 'id');

        return view('users.campaigns.create', [
            'contactGroups' => $contactGroups,
            'senderIds' => $senderIds,
        ]);
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreSmsCampaignRequest $request): RedirectResponse
    {
        // Create campaign with metadata for processing job
        $campaign = auth()->user()->smsCampaigns()->create([
            'title' => $request->title,
            'message' => $request->message,
            'sms_sender_id' => $request->sms_sender_id,
            'total_recipients' => 0,
            'status' => 'pending',
            'scheduled_at' => $request->filled('scheduled_at') ? $request->scheduled_at : null,
            'metadata' => [
                'contact_group_id' => $request->input('contact_group_id'),
            ],
        ]);

        // Dispatch processing job immediately or delayed if scheduled
        if ($request->filled('scheduled_at')) {
            $scheduledTime = \Carbon\Carbon::parse($request->scheduled_at);
            if ($scheduledTime->isFuture()) {
                ProcessSmsCampaignJob::dispatch($campaign)->delay($scheduledTime);
            } else {
                ProcessSmsCampaignJob::dispatch($campaign);
            }
        } else {
            ProcessSmsCampaignJob::dispatch($campaign);
        }

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign created successfully. Messages will be sent shortly.');
    }

    /**
     * Display the specified campaign.
     */
    public function show(Request $request, SmsCampaign $campaign): View
    {
        abort_if(auth()->user()->id !== $campaign->user_id, 403);

        $perPage = $request->query('per_page', 15);

        $messages = $campaign->smsMessages()
            ->with('smsApiLog')
            ->latest()
            ->paginate($perPage);

        $summary = $campaign->getSummary();

        return view('users.campaigns.show', [
            'campaign' => $campaign,
            'messages' => $messages,
            'summary' => $summary,
        ]);
    }

    /**
     * Show the form for editing the specified campaign.
     */
    public function edit(SmsCampaign $campaign): View
    {
        abort_if(auth()->user()->id !== $campaign->user_id, 403);

        return view('users.campaigns.edit', ['campaign' => $campaign]);
    }

    /**
     * Update the specified campaign in storage.
     */
    public function update(UpdateSmsCampaignRequest $request, SmsCampaign $campaign): RedirectResponse
    {
        $campaign->update($request->validated());

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     */
    public function destroy(SmsCampaign $campaign): RedirectResponse
    {
        abort_if(auth()->user()->id !== $campaign->user_id, 403);

        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
