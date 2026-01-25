<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SmsSenderId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SenderIdController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $senderIds = $user->smsSenderIds()->latest()->paginate(10);
        return view('users.sender-ids.index', compact('senderIds'));
    }

    public function create()
    {
        return view('users.sender-ids.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|string|max:11',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        $user->smsSenderIds()->create([
            'sender_id' => $request->sender_id,
            'description' => $request->description,
            'status' => 'inactive',
            'approval_status' => 'pending',
        ]);
        return redirect()->route('users.sender-ids.index')->with('status', 'Sender ID submitted for review.');
    }

    public function edit(SmsSenderId $senderId)
    {
        $this->authorize('update', $senderId);
        return view('users.sender-ids.edit', compact('senderId'));
    }

    public function update(Request $request, SmsSenderId $senderId)
    {
        $this->authorize('update', $senderId);
        $request->validate([
            'sender_id' => 'required|string|max:11',
            'description' => 'required|string',
        ]);

        $senderId->update([
            'sender_id' => $request->sender_id,
            'description' => $request->description,
        ]);

        return redirect()->route('users.sender-ids.index')->with('status', 'Sender ID updated.');
    }

    public function destroy(SmsSenderId $senderId)
    {
        $this->authorize('delete', $senderId);
        $senderId->delete();
        return redirect()->route('users.sender-ids.index')->with('status', 'Sender ID deleted.');
    }
}
