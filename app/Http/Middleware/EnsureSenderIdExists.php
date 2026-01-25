<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureSenderIdExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user && $user->is_client && $user->isApproved() && ! $user->hasSenderIds()) {
            return response()->view('auth.force-sender-id');
        }
        return $next($request);
    }
}
