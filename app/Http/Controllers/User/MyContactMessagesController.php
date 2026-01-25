<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class MyContactMessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'phone.verified']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = $request->query('per_page', 15);

        // Get messages by user's phone number
        $messages = ContactMessage::where('phone', $user->phone)
            ->orWhere('email', $user->email)
            ->latest()
            ->paginate($perPage);

        return view('users.my-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $user = auth()->user();

        // Verify the message belongs to this user
        if ($contactMessage->phone !== $user->phone && $contactMessage->email !== $user->email) {
            abort(403, 'Unauthorized access to this message.');
        }

        // Mark as read when user views it
        $contactMessage->markAsRead();

        return view('users.my-messages.show', compact('contactMessage'));
    }
}
