<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\SmsMessage;

class SmsMessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified']);
    }

    public function index(Request $request): View
    {
        $q = $request->query('q');
        $status = $request->query('status');
        $perPage = $request->query('per_page', 15);

        $smsMessages = SmsMessage::where('user_id', auth()->id())
            ->when($q, fn($qb) => $qb->where('phone', 'like', "%$q%"))
            ->when($status, fn($qb) => $qb->where('status', $status))
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return view('users.sms-messages.index', ['smsMessages' => $smsMessages]);
    }

    public function show(SmsMessage $smsMessage): View
    {
        abort_if($smsMessage->user_id !== auth()->id(), 403);
        return view('users.sms-messages.show', ['smsMessage' => $smsMessage]);
    }
}
