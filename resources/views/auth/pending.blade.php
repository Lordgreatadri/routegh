<x-guest-layout>
    <div class="text-center py-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-yellow-400/20 text-yellow-300 mb-6">
            <!-- simple shield icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l7 4v6c0 5-4 9-7 10-3-1-7-5-7-10V6l7-4z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-white">Your account is under review</h2>
        <p class="mt-3 text-sm text-gray-300 max-w-md mx-auto">Thanks for signing up — an administrator will review your details shortly. You will be notified by email once your account is approved.</p>
        <div class="mt-6">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-4 py-2 bg-white/5 hover:bg-white/10 rounded-md text-white">Logout</a>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</x-guest-layout>
