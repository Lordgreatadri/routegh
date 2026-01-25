<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\PhoneOtpMail;
use App\Services\SmsService;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\UserOtp;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'unverified', // require phone OTP first
        ]);

        event(new Registered($user));

        // Generate OTP and store its hash in cache (configurable expiry)
        $otp = random_int(100000, 999999);
        $expiry = config('services.sms.otp_expiry_minutes', 10);

        // Store hashed OTP in cache, and also persist a database backup record
        $hash = Hash::make((string) $otp);
        Cache::put('phone_otp_hash_'.$user->phone, $hash, now()->addMinutes($expiry));
        Cache::put('phone_otp_exists_'.$user->phone, true, now()->addMinutes($expiry));

        // Persist to user_otps table (hashed) for auditing/future use
        try {
            UserOtp::create([
                'phone_number' => $user->phone,
                'otp_hash' => $hash,
                'expired_at' => now()->addMinutes($expiry),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Failed to persist OTP to DB: ' . $e->getMessage());
        }

        // Send OTP via injected SMS service (falls back to log)
        $sent = $this->smsService->sendOtp($user->phone, (string) $otp);

        // Also send email copy if user provided email (optional)
        if ($user->email) {
            Mail::to($user->email)->queue(new PhoneOtpMail($user, $otp));
        }

        if (! $sent) {
            logger()->warning('OTP SMS was not sent for ' . $user->phone);
        }

        Auth::login($user);

        // Redirect to phone verification form
        return redirect()->route('phone.verify');
    }

    /**
     * Show phone verification form.
     */
    public function showVerifyForm(): View
    {
        return view('auth.verify-phone');
    }

    /**
     * Handle phone verification.
     */
    public function verifyPhone(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Unable to verify phone.');
        }

        // Check if OTP exists
        $hash = Cache::get('phone_otp_hash_'.$user->phone);

        // Implement per-phone attempt counter
        $attemptsKey = 'phone_otp_attempts_'.$user->phone;
        $attempts = Cache::get($attemptsKey, 0);
        $maxAttempts = 5;

        if (! $hash) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        if (! \Illuminate\Support\Facades\Hash::check($request->otp, $hash)) {
            $attempts++;
            Cache::put($attemptsKey, $attempts, now()->addMinutes(config('services.sms.otp_expiry_minutes', 10)));

            if ($attempts >= $maxAttempts) {
                // Invalidate OTP and require resend
                Cache::forget('phone_otp_hash_'.$user->phone);
                Cache::forget('phone_otp_exists_'.$user->phone);
                return back()->withErrors(['otp' => 'Too many attempts. OTP invalidated, please request a new code.']);
            }

            return back()->withErrors(['otp' => 'Invalid code. Attempts: ' . $attempts]);
        }


        // OTP correct
        $user->update([
            'status' => 'pending',
            'phone_verified_at' => now(),
        ]);

        // Clear OTP and attempts from cache
        Cache::forget('phone_otp_hash_'.$user->phone);
        Cache::forget('phone_otp_exists_'.$user->phone);
        Cache::forget($attemptsKey);

        // Remove persisted OTP rows for this phone
        try {
            UserOtp::where('phone_number', $user->phone)->delete();
        } catch (\Throwable $e) {
            logger()->warning('Failed to delete persisted OTPs: ' . $e->getMessage());
        }

        return redirect()->route('pending')->with('success', 'Phone verified. Your account is pending admin approval.');
    }

    /**
     * Resend OTP to the current user's phone. Rate-limited via route middleware.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Unable to resend OTP.');
        }

        // Per-phone resend rate limiting using RateLimiter
        $key = 'resend-otp:'.$user->phone;
        $maxAttempts = config('services.sms.resend_max_attempts', 3);
        $decaySeconds = config('services.sms.resend_decay_seconds', 900); // default 15 minutes

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['otp' => 'Too many resend attempts. Try again in '.$seconds.' seconds.']);
        }

        // Generate a new OTP and replace hash
        $otp = random_int(100000, 999999);
        $expiry = config('services.sms.otp_expiry_minutes', 10);
        $hash = Hash::make((string) $otp);

        Cache::put('phone_otp_hash_'.$user->phone, $hash, now()->addMinutes($expiry));
        Cache::put('phone_otp_exists_'.$user->phone, true, now()->addMinutes($expiry));
        Cache::forget('phone_otp_attempts_'.$user->phone);

        // Persist/update DB OTP row
        try {
            // delete old OTPs for phone and insert new one
            UserOtp::where('phone_number', $user->phone)->delete();
            UserOtp::create([
                'phone_number' => $user->phone,
                'otp_hash' => $hash,
                'expired_at' => now()->addMinutes($expiry),
            ]);
        } catch (\Throwable $e) {
            logger()->warning('Failed to persist OTP to DB on resend: ' . $e->getMessage());
        }

        $sent = $this->smsService->sendOtp($user->phone, (string) $otp);

        if ($user->email) {
            Mail::to($user->email)->queue(new PhoneOtpMail($user, $otp));
        }

        if (! $sent) {
            logger()->warning('Resend OTP SMS was not sent for ' . $user->phone);
        }

        // Record the resend attempt
        RateLimiter::hit($key, $decaySeconds);

        return back()->with('status', 'A new verification code has been sent.');
    }
}
