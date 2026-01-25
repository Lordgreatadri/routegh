<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Message;

class MessageController extends Controller
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

        $messages = Message::where('user_id', auth()->id())
            ->when($q, fn($qb) => $qb->where('recipient_number', 'like', "%$q%"))
            ->when($status, fn($qb) => $qb->where('status', $status))
            ->latest()
            ->paginate($perPage)
            ->appends($request->query());

        return view('users.messages.index', ['messages' => $messages]);
    }

    public function show(Message $message): View
    {
        abort_if($message->user_id !== auth()->id(), 403);
        return view('users.messages.show', ['message' => $message]);
    }
}
