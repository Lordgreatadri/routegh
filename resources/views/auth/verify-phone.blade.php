<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-white">Verify your phone</h2>
        <p class="text-sm text-gray-300">Enter the 6-digit code sent to your phone.</p>
    </div>

    <form method="POST" action="{{ route('phone.verify.post') }}" class="space-y-4">
        @csrf

        <div>
            <label for="otp" class="block text-sm font-medium text-gray-200">Verification code</label>
            <x-text-input id="otp" class="mt-1 block w-full rounded-md bg-white/5 border border-white/10 text-white placeholder-gray-400" type="text" name="otp" :value="old('otp')" required autofocus />
            <x-input-error :messages="$errors->get('otp')" class="mt-2 text-sm text-red-400" />
        </div>

        <div>
            <x-primary-button class="w-full py-2">Verify phone</x-primary-button>
        </div>
    </form>
    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('phone.resend') }}">
            @csrf
            <button type="submit" class="text-sm text-indigo-300 hover:underline">Resend code</button>
        </form>
    </div>
</x-guest-layout>
