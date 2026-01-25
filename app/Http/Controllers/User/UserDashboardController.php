<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->is_client) {
            abort(403);
        }

        if (is_null($user->phone_verified_at)) {
            return redirect()->route('phone.verify')->with('status', 'Please verify your phone number first.');
        }

        if (! $user->isApproved()) {
            return redirect()->route('pending')->with('status', 'Your account is pending approval.');
        }

        // If user is approved and is client, check sender ids
        if ($user->isApproved() && $user->isClient()) {
            if (! $user->hasSenderIds()) {
                return redirect()->route('users.sender-ids.create')->with('status', 'You need to create a Sender ID before using SMS features. Your request will be reviewed by an admin.');
            }
        }

        // Get statistics
        $stats = [
            'total_messages' => $user->smsMessages()->count(),
            'messages_sent' => $user->smsMessages()->where('status', 'sent')->count(),
            'messages_delivered' => $user->smsMessages()->where('status', 'delivered')->count(),
            'messages_failed' => $user->smsMessages()->where('status', 'failed')->count(),
            'total_contacts' => $user->contacts()->count(),
            'total_groups' => $user->contactGroups()->count(),
            'total_campaigns' => $user->smsCampaigns()->count(),
            'campaigns_active' => $user->smsCampaigns()->where('status', 'processing')->count(),
            'campaigns_completed' => $user->smsCampaigns()->where('status', 'completed')->count(),
        ];

        // Recent campaigns
        $recentCampaigns = $user->smsCampaigns()->latest()->take(5)->get();

        return view('users.dashboard', compact('stats', 'recentCampaigns'));
    }
}
