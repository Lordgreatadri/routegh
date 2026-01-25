<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\SmsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
        ]);

        // Check if user exists and is verified
        $user = User::where('phone', $request->phone)
            ->whereNotNull('phone_verified_at')
            ->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'No verified account found with this phone number.']);
        }

        // Generate OTP and store its hash in cache
        $otp = random_int(100000, 999999);
        $expiry = config('services.sms.otp_expiry_minutes', 10);

        // Store hashed OTP in cache
        $hash = Hash::make((string) $otp);
        Cache::put('password_reset_otp_hash_'.$request->phone, $hash, now()->addMinutes($expiry));
        Cache::put('password_reset_otp_exists_'.$request->phone, true, now()->addMinutes($expiry));

        // Persist to user_otps table for auditing
        try {
            UserOtp::create([
                'phone_number' => $request->phone,
                'otp_hash' => $hash,
                'expired_at' => now()->addMinutes($expiry),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Failed to persist password reset OTP to DB: ' . $e->getMessage());
        }

        // Send OTP via SMS
        try {
            $message = "Your password reset OTP is: {$otp}. Valid for {$expiry} minutes.";
            $this->smsService->sendMessage($request->phone, $message);
        } catch (\Throwable $e) {
            logger()->error('Failed to send password reset OTP: ' . $e->getMessage());
            return back()->withErrors(['phone' => 'Failed to send OTP. Please try again.']);
        }

        return redirect()->route('password.verify-otp', ['phone' => $request->phone])
            ->with('status', 'OTP has been sent to your phone number.');
    }
}
