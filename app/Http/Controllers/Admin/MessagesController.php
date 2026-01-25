<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsMessage;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $q = request('q');
        $perPage = $request->query('per_page', 10);
        $messages = SmsMessage::when($q, function ($qb, $val) {
            $qb->where('phone', 'like', "%{$val}%")->orWhere('message', 'like', "%{$val}%");
        })->latest()->paginate($perPage)->appends(request()->query());
        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $senderIds = \App\Models\SmsSenderId::with('user')
            ->where('approval_status', 'approved')
            ->where('status', 'active')
            ->orderBy('sender_id')
            ->get();
        return view('admin.messages.create', compact('senderIds'));
    }

    public function show(SmsMessage $message)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $message->load('user');
        return view('admin.messages.show', compact('message'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate([
            'phone' => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9]+$/'],
            'message' => ['required', 'string', 'min:1', 'max:160'],
            'sms_sender_id' => ['required', 'exists:sms_sender_ids,id'],
        ], [
            'phone.required' => 'Phone number is required',
            'phone.min' => 'Phone number must be at least 10 digits',
            'phone.max' => 'Phone number must not exceed 15 digits',
            'phone.regex' => 'Phone number must contain only digits',
            'message.required' => 'Message content is required',
            'message.min' => 'Message must be at least 1 character',
            'message.max' => 'Message must not exceed 160 characters',
            'sms_sender_id.required' => 'Sender ID is required',
            'sms_sender_id.exists' => 'Selected Sender ID is invalid',
        ]);

        $smsMessage = SmsMessage::create([
            'phone' => $data['phone'],
            'message' => $data['message'],
            'status' => 'queued',
            'sms_campaign_id' => null, // Individual messages aren't part of a campaign
            'user_id' => auth()->id(), // Associate with the admin creating the message
            'contact_id' => null, // Individual messages may not be associated with a contact
            'sms_sender_id' => $data['sms_sender_id'],
        ]);

        // Dispatch the job to send the message
        \App\Jobs\SendSmsMessageJob::dispatch($smsMessage)->onQueue('sms');

        return redirect()->route('admin.messages.index')->with('success', 'Message queued and will be sent shortly');
    }




    public function edit(SmsMessage $message)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        return view('admin.messages.edit', compact('message'));
    }




    public function update(Request $request, SmsMessage $message)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $data = $request->validate([
            'message' => 'required|string',
            'status' => 'nullable|string',
        ]);
        $message->update($data);
        return redirect()->route('admin.messages.index')->with('success', 'Message updated');
    }


    
    public function destroy(SmsMessage $message)
    {
        abort_if(!auth()->user()->isAdmin(), 403);
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted');
    }
}
