<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function createOtpVerify(Request $request): View
    {
        return view('auth.verify-reset-otp', ['phone' => $request->phone]);
    }

    /**
     * Verify OTP and show password reset form.
     */
    public function verifyOtp(Request $request): RedirectResponse|View
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        // Check if OTP exists in cache
        $cachedHash = Cache::get('password_reset_otp_hash_'.$request->phone);

        if (!$cachedHash) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Verify OTP
        if (!Hash::check($request->otp, $cachedHash)) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // OTP is valid, store phone in session and show password reset form
        $request->session()->put('password_reset_phone', $request->phone);
        $request->session()->put('password_reset_verified', true);

        // Delete the OTP from cache
        Cache::forget('password_reset_otp_hash_'.$request->phone);
        Cache::forget('password_reset_otp_exists_'.$request->phone);

        return view('auth.reset-password', ['phone' => $request->phone]);
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        // Check if OTP was verified
        if (!$request->session()->has('password_reset_verified')) {
            return redirect()->route('password.request')
                ->withErrors(['phone' => 'Please verify your phone number first.']);
        }

        return view('auth.reset-password', ['phone' => $request->session()->get('password_reset_phone')]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Verify session
        if (!$request->session()->has('password_reset_verified')) {
            return redirect()->route('password.request')
                ->withErrors(['phone' => 'Session expired. Please start again.']);
        }

        $request->validate([
            'phone' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Verify phone matches session
        $sessionPhone = $request->session()->get('password_reset_phone');
        if ($request->phone !== $sessionPhone) {
            return redirect()->route('password.request')
                ->withErrors(['phone' => 'Invalid request. Please start again.']);
        }

        // Find user and update password
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'User not found.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->save();

        event(new PasswordReset($user));

        // Clear session
        $request->session()->forget('password_reset_phone');
        $request->session()->forget('password_reset_verified');

        return redirect()->route('login')->with('status', 'Your password has been reset successfully.');
    }
}
