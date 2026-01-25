<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use Illuminate\Support\Str;
use App\Models\SmsMessage;
use App\Models\SmsCampaign;
use App\Models\Contact;
use App\Models\ContactGroup;

class UserApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display pending users for approval.
     */
    public function pending(Request $request): View
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $perPage = $request->query('per_page', 15);
        $users = User::where('status', 'pending')
            ->latest()
            ->paginate($perPage);

        return view('admin.users.pending', ['users' => $users]);
    }

    /**
     * Display all users (admin dashboard).
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = request('q');
        $perPage = $request->query('per_page', 15);
        $users = User::when($search, function ($qb, $q) {
            $qb->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%");
        })->latest()->paginate($perPage)->appends(request()->query());
        $stats = [
            'total_users' => User::count(),
            'pending_approvals' => User::where('status', 'pending')->count(),
            'approved_users' => User::where('status', 'approved')->count(),
            'rejected_users' => User::where('status', 'rejected')->count(),
            // Messaging stats
            'messages_total' => SmsMessage::count(),
            'messages_sent' => SmsMessage::where('status', 'sent')->count(),
            'messages_delivered' => SmsMessage::where('status', 'delivered')->count(),
            'campaigns_total' => SmsCampaign::count(),
            'contacts_total' => Contact::count(),
            'groups_total' => ContactGroup::count(),
        ];

        // Recent activity for system metrics
        $recentUploads = \App\Models\Upload::latest()->take(5)->get();
        $recentCampaigns = \App\Models\SmsCampaign::latest()->take(5)->get();

        return view('admin.users.index', [
            'users' => $users,
            'stats' => $stats,
            'recentUploads' => $recentUploads,
            'recentCampaigns' => $recentCampaigns,
        ]);
    }

    /**
     * Permanently delete a user (admin action).
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        // prevent admin deleting themselves accidentally
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->back()->with('success', "User {$user->name} deleted.");
    }

    /**
     * Assign a role to a user.
     */
    public function assignRole(Request $request, User $user): RedirectResponse
    {
        abort_if(!auth()->user()->isAdmin(), 403);

        $data = $request->validate([
            'role' => ['required', 'string', 'in:user,admin,moderator,support'],
        ]);

        // Update the role column in users table for backward compatibility
        $user->update(['role' => $data['role']]);

        // Sync with Spatie permission system
        // This removes all existing roles and assigns the new one
        $user->syncRoles([$data['role']]);

        return redirect()->back()->with('success', "Role for {$user->name} updated to {$data['role']}.");
    }

    /**
     * Approve a user.
     */
    public function approve(User $user): RedirectResponse
    {
        $this->authorize('approve', $user);

        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Queue approval email
        Mail::to($user->email)->queue(new UserApprovedMail($user));

        return redirect()->back()
            ->with('success', "User {$user->name} approved successfully.");
    }

    /**
     * Reject a user.
     */
    public function reject(Request $request, User $user): RedirectResponse
    {
        $this->authorize('reject', $user);

        $user->update(['status' => 'rejected']);

        // Queue rejection email
        Mail::to($user->email)->queue(new UserRejectedMail($user));

        return redirect()->back()
            ->with('success', "User {$user->name} rejected.");
    }
}
