<?php

namespace App\Http\Controllers;

use App\Models\SmsCampaign;
use App\Models\SmsMessage;
use App\Models\Contact;
use App\Models\Upload;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index(): View
    {
        // Check if user is approved
        if (!auth()->user()->isApproved()) {
            return view('dashboard.pending-approval');
        }

        // Check if user is admin
        if (auth()->user()->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->userDashboard();
    }

    /**
     * User dashboard view.
     */
    protected function userDashboard(): View
    {
        $user = auth()->user();
        
        $stats = [
            'total_messages_sent' => $user->smsMessages()->where('status', 'sent')->count(),
            'total_recipients' => $user->contacts()->count(),
            'messages_today' => $user->smsMessages()
                ->whereDate('sent_at', now())
                ->where('status', 'sent')
                ->count(),
            'active_campaigns' => $user->smsCampaigns()
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'failed_messages' => $user->smsMessages()
                ->where('status', 'failed')
                ->count(),
        ];

        $recentCampaigns = $user->smsCampaigns()
            ->latest()
            ->limit(5)
            ->get();

        $recentUploads = $user->uploads()
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.user', [
            'stats' => $stats,
            'recentCampaigns' => $recentCampaigns,
            'recentUploads' => $recentUploads,
        ]);
    }

    /**
     * Admin dashboard view.
     */
    protected function adminDashboard(): View
    {
        $stats = [
            'total_messages_sent' => SmsMessage::where('status', 'sent')->count(),
            'total_active_users' => $this->getActiveUsersCount(),
            'pending_approvals' => \App\Models\User::where('status', 'pending')->count(),
            'total_recipients' => Contact::count(),
            'total_uploads' => Upload::count(),
            'failed_messages' => SmsMessage::where('status', 'failed')->count(),
            'delivered_messages' => SmsMessage::where('status', 'delivered')->count(),
        ];

        $recentUsers = \App\Models\User::where('status', 'approved')
            ->latest('last_login_at')
            ->limit(10)
            ->get();

        $pendingUsers = \App\Models\User::where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.admin', [
            'stats' => $stats,
            'recentUsers' => $recentUsers,
            'pendingUsers' => $pendingUsers,
        ]);
    }

    /**
     * Get count of active users (those with campaigns or messages in last 30 days).
     */
    protected function getActiveUsersCount(): int
    {
        return \App\Models\User::where('status', 'approved')
            ->where('last_login_at', '>=', now()->subDays(30))
            ->count();
    }
}
