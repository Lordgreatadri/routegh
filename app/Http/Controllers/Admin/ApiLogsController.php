<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsApiLog;

class ApiLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $provider = $request->query('provider');
        $perPage = max(10, $request->query('per_page', 10));
        
        $logs = SmsApiLog::with('smsMessage')
            ->when($provider, function ($qb, $val) {
                $qb->where('provider', $val);
            })
            ->latest('id')
            ->paginate($perPage)
            ->appends(request()->query());

        $stats = [
            'total_logs' => SmsApiLog::count(),
            'today_logs' => SmsApiLog::whereDate('created_at', today())->count(),
            'providers' => SmsApiLog::distinct('provider')->pluck('provider')->filter()->toArray(),
        ];

        return view('admin.api-logs.index', compact('logs', 'stats'));
    }

    public function show(SmsApiLog $apiLog)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        
        $apiLog->load('smsMessage.contact', 'smsMessage.smsCampaign');
        
        return view('admin.api-logs.show', compact('apiLog'));
    }

    public function clearLogs(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'clear_before_date' => 'required|date|before_or_equal:today',
        ]);

        $date = $request->input('clear_before_date');
        
        $deletedCount = SmsApiLog::whereDate('created_at', '<=', $date)->delete();

        return redirect()->route('admin.api-logs.index')
            ->with('success', "Successfully deleted {$deletedCount} API log(s) from {$date} and earlier.");
    }
}
