<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-white">Verify OTP</h2>
        <p class="text-sm text-gray-300 mt-2">Enter the 6-digit code sent to {{ $phone }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.verify-otp.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">

        <!-- OTP Input -->
        <div>
            <label for="otp" class="block text-sm font-medium text-gray-200">OTP Code</label>
            <x-text-input id="otp" 
                class="mt-1 block w-full rounded-md bg-white/5 border border-white/10 text-white placeholder-gray-400 text-center text-2xl tracking-widest" 
                type="text" 
                name="otp" 
                :value="old('otp')" 
                required 
                autofocus 
                maxlength="6"
                placeholder="000000" />
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-sm text-red-400" />
        </div>

        <div class="flex items-center justify-between">
            <a class="text-sm text-indigo-200 hover:underline" href="{{ route('password.request') }}">Resend OTP</a>
            <x-primary-button class="!bg-blue-500 hover:!bg-blue-600 focus:!bg-blue-600 active:!bg-blue-700">
                {{ __('Verify OTP') }}
            </x-primary-button>
        </div>

        <p class="text-center text-sm text-gray-400 mt-4">
            Didn't receive the code? <a href="{{ route('password.request') }}" class="text-indigo-300 hover:underline">Send again</a>
        </p>
    </form>
</x-guest-layout>
