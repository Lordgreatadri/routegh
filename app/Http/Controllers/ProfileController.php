<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\PhoneOtpMail;
use App\Services\SmsService;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $phoneChanged = isset($validated['phone']) && $validated['phone'] !== $user->phone;
        $emailChanged = isset($validated['email']) && $validated['email'] !== $user->email;

        $user->fill($validated);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        if ($phoneChanged) {
            $user->phone_verified_at = null;
            // Mark as unverified and require re-verification
            $user->status = 'unverified';
        }

        $user->save();

        // If phone changed, generate and send a new OTP and redirect to verify flow
        if ($phoneChanged) {
            $otp = random_int(100000, 999999);
            $expiry = config('services.sms.otp_expiry_minutes', 10);
            $hash = Hash::make((string) $otp);

            Cache::put('phone_otp_hash_'.$user->phone, $hash, now()->addMinutes($expiry));
            Cache::put('phone_otp_exists_'.$user->phone, true, now()->addMinutes($expiry));
            Cache::forget('phone_otp_attempts_'.$user->phone);

            $sms = new SmsService();
            $sms->sendOtp($user->phone, (string) $otp);

            if ($user->email) {
                Mail::to($user->email)->queue(new PhoneOtpMail($user, $otp));
            }

            return Redirect::route('phone.verify')->with('status', 'Phone changed. A new verification code was sent.');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Flash a message so front-end can show a toast if applicable.
        $request->session()->flash('accountDeleted', 'Your account has been deleted.');

        return Redirect::to('/');
    }
}
