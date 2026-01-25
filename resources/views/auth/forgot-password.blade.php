<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-white">Forgot your password?</h2>
        <p class="text-sm text-gray-300 mt-2">Enter your phone number and we'll send you an OTP to reset your password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Phone Number -->
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-200">Phone Number</label>
            <x-text-input id="phone" class="mt-1 block w-full rounded-md bg-white/5 border border-white/10 text-white placeholder-gray-400" 
                type="text" 
                name="phone" 
                :value="old('phone')" 
                required 
                autofocus 
                placeholder="Enter your registered phone number" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-red-400" />
        </div>

        <div class="flex items-center justify-between">
            <a class="text-sm text-indigo-200 hover:underline" href="{{ route('login') }}">Back to login</a>
            <x-primary-button class="!bg-blue-500 hover:!bg-blue-600 focus:!bg-blue-600 active:!bg-blue-700">
                {{ __('Send OTP') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
