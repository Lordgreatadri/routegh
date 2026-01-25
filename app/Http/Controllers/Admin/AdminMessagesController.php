<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminMessage;
use App\Models\User;

class AdminMessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function index(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $search = $request->query('search');

        // Get users who have messages, with their latest message
        $conversations = User::whereHas('adminMessages')
            ->when($search, function ($query, $val) {
                $query->where(function ($q) use ($val) {
                    $q->where('name', 'like', "%{$val}%")
                      ->orWhere('email', 'like', "%{$val}%")
                      ->orWhere('phone', 'like', "%{$val}%");
                });
            })
            ->with(['adminMessages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->withCount(['adminMessages as unread_count' => function ($query) {
                $query->where('is_from_admin', false)->where('status', 'unread');
            }])
            ->paginate(20)
            ->appends(request()->query());

        $stats = [
            'total_conversations' => User::whereHas('adminMessages')->count(),
            'unread_messages' => AdminMessage::where('is_from_admin', false)->unread()->count(),
            'total_messages' => AdminMessage::count(),
        ];

        return view('admin.admin-messages.index', compact('conversations', 'stats'));
    }

    public function show(User $user)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $messages = AdminMessage::conversation($user->id)->get();

        // Mark user messages as read
        AdminMessage::where('user_id', $user->id)
            ->where('is_from_admin', false)
            ->unread()
            ->each(fn($msg) => $msg->markAsRead());

        return view('admin.admin-messages.show', compact('user', 'messages'));
    }

    public function store(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|min:1|max:1000',
        ]);

        $user = User::findOrFail($request->user_id);

        $message = AdminMessage::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->id(),
            'message' => $request->message,
            'is_from_admin' => true,
            'status' => 'unread',
        ]);

        // If this is a reply to a user message, mark the last user message as replied
        $lastUserMessage = AdminMessage::where('user_id', $request->user_id)
            ->where('is_from_admin', false)
            ->where('status', 'read')
            ->latest()
            ->first();

        if ($lastUserMessage) {
            $lastUserMessage->markAsReplied();
        }

        return redirect()->route('admin.admin-messages.show', $user)
            ->with('success', 'Message sent successfully.');
    }

    public function compose()
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $users = User::where('id', '!=', auth()->id())
            ->where('role', '!=', 'admin')
            ->orderBy('name')
            ->get();

        return view('admin.admin-messages.compose', compact('users'));
    }

    public function clearMessages(Request $request)
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $request->validate([
            'clear_before_date' => 'required|date|before_or_equal:today',
        ]);

        $date = $request->input('clear_before_date');
        
        $deletedCount = AdminMessage::whereDate('created_at', '<=', $date)->delete();

        return redirect()->route('admin.admin-messages.index')
            ->with('success', "Successfully deleted {$deletedCount} message(s) from {$date} and earlier.");
    }
}
