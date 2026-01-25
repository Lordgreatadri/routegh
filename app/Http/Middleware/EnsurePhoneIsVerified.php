<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePhoneIsVerified
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

        if (is_null($user->phone_verified_at)) {
            return redirect()->route('phone.verify')->with('status', 'Please verify your phone number.');
        }

        return $next($request);
    }
}
