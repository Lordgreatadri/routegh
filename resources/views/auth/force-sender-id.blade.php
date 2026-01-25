<x-guest-layout>
    <div class="text-center py-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-400/20 text-blue-300 mb-6">
            <!-- sender id icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 01-8 0M12 3v4m0 0a4 4 0 01-4 4H5a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2h-3a4 4 0 01-4-4z" />
            </svg>
        </div>
        <h2 class="text-xl font-semibold text-white">Sender ID Required</h2>
        <p class="mt-3 text-sm text-gray-300 max-w-md mx-auto">Your account is approved, but you must create a Sender ID before accessing the dashboard. Please fill the form below to submit your Sender ID for review.</p>
        <div class="mt-8 max-w-lg mx-auto">
            <form action="{{ route('users.sender-ids.store') }}" method="POST" class="bg-slate-800 shadow-lg rounded-lg p-8">
                @csrf
                <div class="mb-6">
                    <label for="sender_id" class="block text-slate-200 font-semibold mb-2">Sender ID <span class="text-red-400">*</span></label>
                    <input type="text" name="sender_id" id="sender_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required maxlength="11" placeholder="e.g. MYBRAND">
                </div>
                <div class="mb-6">
                    <label for="description" class="block text-slate-200 font-semibold mb-2">Description</label>
                    <textarea name="description" id="description" rows="3" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe the purpose of this Sender ID"></textarea>
                </div>
                <div class="mb-6">
                    <span class="block text-yellow-400 bg-yellow-900/40 rounded px-3 py-2 text-sm">
                        Your Sender ID request will be reviewed by an admin. You will be notified once approved or rejected.<br>
                        <span class="block mt-2 text-yellow-200">
                            <strong>Requirements:</strong><br>
                            • Must have a maximum of 11 characters<br>
                            • Must <span class="font-bold">NOT</span> include any special characters (e.g. <span class="font-mono">!&quot;#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~</span>)
                        </span>
                    </span>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Submit for Review</button>
            </form>
        </div>
        <div class="mt-6">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-4 py-2 bg-white/5 hover:bg-white/10 rounded-md text-white">Logout</a>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
    </div>
</x-guest-layout>
