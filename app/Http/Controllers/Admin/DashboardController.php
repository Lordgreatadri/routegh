<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use App\Models\User;
use App\Models\SmsMessage;
use App\Models\SmsCampaign;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\SmsApiLog;
use App\Models\SystemMetric;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(): View
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $stats = [
            'total_users' => User::count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'approved_users' => User::where('status', 'approved')->count(),
            'messages_total' => SmsMessage::count(),
            'messages_sent' => SmsMessage::where('status', 'sent')->count(),
            'messages_delivered' => SmsMessage::where('status', 'delivered')->count(),
            'messages_failed' => SmsMessage::where('status', 'failed')->count(),
            'messages_queued' => SmsMessage::where('status', 'queued')->count(),
            'campaigns_total' => SmsCampaign::count(),
            'campaigns_active' => SmsCampaign::where('status', 'active')->count(),
            'contacts_total' => Contact::count(),
            'groups_total' => ContactGroup::count(),
            'api_calls_today' => \App\Models\SmsApiLog::whereDate('created_at', today())->count(),
        ];

        // prepare 7-day trend for messages
        $from = now()->subDays(6)->startOfDay();
        $trend = \App\Models\SmsMessage::selectRaw("DATE(created_at) as day, count(*) as total")
            ->where('created_at', '>=', $from)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $labels = [];
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $d = $from->copy()->addDays($i)->toDateString();
            $labels[] = $from->copy()->addDays($i)->format('M j');
            $data[] = $trend->has($d) ? $trend->get($d)->total : 0;
        }

        // Get recent pending users
        $recentPendingUsers = User::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'phone', 'created_at']);

        // Get recent campaigns
        $recentCampaigns = SmsCampaign::latest()
            ->take(5)
            ->get(['id', 'title', 'status', 'created_at', 'scheduled_at']);

        // Get system metrics - today and last 7 days
        $todayMetrics = SystemMetric::whereDate('date', today())->first();
        $last7DaysMetrics = SystemMetric::where('date', '>=', now()->subDays(6)->startOfDay())
            ->orderBy('date', 'asc')
            ->get();

        $metricsData = [
            'today' => [
                'messages_sent' => $todayMetrics->total_messages_sent ?? 0,
                'uploads' => $todayMetrics->total_uploads ?? 0,
                'recipients' => $todayMetrics->total_recipients ?? 0,
            ],
            'last_7_days' => [
                'messages_sent' => $last7DaysMetrics->sum('total_messages_sent'),
                'uploads' => $last7DaysMetrics->sum('total_uploads'),
                'recipients' => $last7DaysMetrics->sum('total_recipients'),
            ],
            'chart_labels' => $last7DaysMetrics->pluck('date')->map(fn($d) => $d->format('M j'))->toArray(),
            'chart_messages' => $last7DaysMetrics->pluck('total_messages_sent')->toArray(),
            'chart_uploads' => $last7DaysMetrics->pluck('total_uploads')->toArray(),
        ];

        return view('admin.dashboard', compact('stats', 'recentPendingUsers', 'recentCampaigns', 'metricsData'))
            ->with(['trendLabels' => $labels, 'trendData' => $data]);
    }
}
