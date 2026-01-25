<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminMessage;

class UserMessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get all messages in the conversation
        $messages = AdminMessage::where('user_id', $user->id)
            ->with('admin')
            ->orderBy('created_at', 'asc')
            ->get();

        // Count unread admin messages
        $unreadCount = AdminMessage::where('user_id', $user->id)
            ->where('is_from_admin', true)
            ->where('status', 'unread')
            ->count();

        // Mark admin messages as read
        AdminMessage::where('user_id', $user->id)
            ->where('is_from_admin', true)
            ->where('status', 'unread')
            ->update([
                'status' => 'read',
                'read_at' => now(),
            ]);

        $stats = [
            'total_messages' => $messages->count(),
            'unread_count' => $unreadCount,
            'admin_messages' => $messages->where('is_from_admin', true)->count(),
            'my_messages' => $messages->where('is_from_admin', false)->count(),
        ];

        return view('users.support.index', compact('messages', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1|max:1000',
        ]);

        AdminMessage::create([
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_from_admin' => false,
            'status' => 'unread',
        ]);

        return redirect()->route('user-messages.index')
            ->with('success', 'Message sent to admin successfully.');
    }
}
