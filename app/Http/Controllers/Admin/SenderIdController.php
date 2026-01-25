<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmsSenderId;
use App\Models\User;
use Illuminate\Http\Request;

class SenderIdController extends Controller
{
    public function index()
    {
        $senderIds = SmsSenderId::with('user')->latest()->paginate(20);
        return view('admin.sender-ids.index', compact('senderIds'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.sender-ids.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sender_id' => 'required|string|max:11',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);
        SmsSenderId::create($request->all());
        return redirect()->route('admin.sender-ids.index')->with('status', 'Sender ID created.');
    }

    public function edit(SmsSenderId $senderId)
    {
        $users = User::orderBy('name')->get();
        return view('admin.sender-ids.edit', compact('senderId', 'users'));
    }

    public function update(Request $request, SmsSenderId $senderId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'sender_id' => 'required|string|max:11',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'approval_status' => 'required|in:pending,approved,rejected',
        ]);
        $senderId->update($request->all());
        return redirect()->route('admin.sender-ids.index')->with('status', 'Sender ID updated.');
    }

    public function destroy(SmsSenderId $senderId)
    {
        $senderId->delete();
        return redirect()->route('admin.sender-ids.index')->with('status', 'Sender ID deleted.');
    }
}
