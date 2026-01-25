<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsCampaign;
use App\Models\ContactGroup;
use App\Models\Contact;
use App\Models\SmsMessage;

class CampaignsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $q = request('q');
        $status = $request->query('status');
        $perPage = max(10, $request->query('per_page', 10));
        
        $campaigns = SmsCampaign::with('user')
            ->withCount('smsMessages')
            ->when($q, function ($qb, $val) {
                $qb->where('title', 'like', "%{$val}%")
                   ->orWhere('message', 'like', "%{$val}%");
            })
            ->when($status, function ($qb, $val) {
                $qb->where('status', $val);
            })
            ->latest()
            ->paginate($perPage)
            ->appends(request()->query());

        $stats = [
            'total_campaigns' => SmsCampaign::count(),
            'pending_campaigns' => SmsCampaign::where('status', 'pending')->count(),
            'completed_campaigns' => SmsCampaign::where('status', 'completed')->count(),
            'total_messages_sent' => SmsMessage::whereNotNull('sms_campaign_id')->where('status', 'sent')->count(),
        ];

        return view('admin.campaigns.index', compact('campaigns', 'stats'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $groups = ContactGroup::withCount('contacts')->orderBy('name')->get();
        // Get all approved and active sender IDs with user info
        $senderIds = \App\Models\SmsSenderId::with('user')
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->orderBy('sender_id')
            ->get();
        return view('admin.campaigns.create', compact('groups', 'senderIds'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'sms_sender_id' => 'required|exists:sms_sender_ids,id',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // Count recipients
        $recipientsCount = 0;
        $groupId = $validated['contact_group_id'] ?? null;
        
        if ($groupId) {
            $group = ContactGroup::find($groupId);
            if ($group) {
                $recipientsCount = $group->contacts()->count();
            }
        } else {
            $recipientsCount = Contact::count();
        }

        $campaign = SmsCampaign::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'message' => $validated['message'],
            'sms_sender_id' => $validated['sms_sender_id'],
            'status' => isset($validated['scheduled_at']) ? 'processing' : 'pending',
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'total_recipients' => $recipientsCount, // Will be updated by ProcessSmsCampaignJob
            'metadata' => [
                'contact_group_id' => $groupId,
                'created_via' => 'admin',
            ],
        ]);

        // For instant campaigns (no scheduled_at), dispatch the job immediately
        if (empty($validated['scheduled_at'])) {
            \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign);
        } else {
            // Schedule for later
            $scheduledTime = \Carbon\Carbon::parse($validated['scheduled_at']);
            if ($scheduledTime->isFuture()) {
                \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign)->delay($scheduledTime);
            } else {
                \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign);
            }
        }

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', "Campaign '{$campaign->title}' created successfully and will be processed shortly.");
    }

    public function show(SmsCampaign $campaign)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $campaign->load('user');
        $campaign->loadCount('smsMessages');
        
        $messages = $campaign->smsMessages()
            ->with(['contact', 'smsApiLog'])
            ->latest()
            ->paginate(10);

        $messageStats = [
            'total' => $campaign->sms_messages_count,
            'queued' => $campaign->smsMessages()->where('status', 'queued')->count(),
            'sent' => $campaign->smsMessages()->where('status', 'sent')->count(),
            'failed' => $campaign->smsMessages()->where('status', 'failed')->count(),
            'delivered' => $campaign->smsMessages()->where('status', 'delivered')->count(),
        ];

        return view('admin.campaigns.show', compact('campaign', 'messages', 'messageStats'));
    }

    public function edit(SmsCampaign $campaign)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        // Only allow editing if campaign is pending or scheduled
        if (!in_array($campaign->status, ['pending', 'scheduled'])) {
            return redirect()
                ->route('admin.campaigns.index')
                ->with('error', 'Cannot edit a campaign that is already processing or completed.');
        }

        $groups = ContactGroup::withCount('contacts')->orderBy('name')->get();
        return view('admin.campaigns.edit', compact('campaign', 'groups'));
    }

    public function update(Request $request, SmsCampaign $campaign)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        // Only allow editing if campaign is pending or scheduled
        if (!in_array($campaign->status, ['pending', 'scheduled', 'processing'])) {
            return redirect()
                ->route('admin.campaigns.index')
                ->with('error', 'Cannot edit a campaign that is already processing or completed.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'contact_group_id' => 'nullable|exists:contact_groups,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        // Recalculate recipients if group changed
        $metadata = $campaign->metadata ?? [];
        $oldGroupId = $metadata['contact_group_id'] ?? null;
        $newGroupId = $validated['contact_group_id'] ?? null;

        if ($oldGroupId != $newGroupId) {
            $recipientsCount = 0;
            if (!empty($newGroupId)) {
                $recipientsCount = Contact::where('contact_group_id', $newGroupId)->count();
            } else {
                $recipientsCount = Contact::count();
            }
            $campaign->total_recipients = $recipientsCount;
        }

        $campaign->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'status' => $validated['scheduled_at'] ?? null ? 'processing' : 'pending',
            'scheduled_at' => $validated['scheduled_at'] ?? null,
            'metadata' => array_merge($metadata, [
                'contact_group_id' => $newGroupId,
            ]),
        ]);


        // For instant campaigns (no scheduled_at), dispatch the job immediately
        if (empty($validated['scheduled_at'])) {
            \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign);
        } else {
            // Schedule for later
            $scheduledTime = \Carbon\Carbon::parse($validated['scheduled_at']);
            if ($scheduledTime->isFuture()) {
                \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign)->delay($scheduledTime);
            } else {
                \App\Jobs\ProcessSmsCampaignJob::dispatch($campaign);
            }
        }

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', "Campaign '{$campaign->title}' updated successfully.");
    }

    public function destroy(SmsCampaign $campaign)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        // Prevent deletion of processing campaigns
        if ($campaign->status === 'processing') {
            return redirect()
                ->route('admin.campaigns.index')
                ->with('error', 'Cannot delete a campaign that is currently processing.');
        }

        $title = $campaign->title;
        $campaign->delete();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', "Campaign '{$title}' deleted successfully.");
    }
}
