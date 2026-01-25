<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Only allow users with status 'approved' to proceed to protected routes.
        if ($user->status !== 'approved') {
            return redirect()->route('pending');
        }

        return $next($request);
    }
}
