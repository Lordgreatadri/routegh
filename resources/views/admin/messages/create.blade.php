<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Send Single SMS Message</h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">For bulk messaging, use <a href="{{ route('admin.campaigns.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Campaigns</a></p>
            </div>
            <a href="{{ route('admin.messages.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Messages
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Message Details</h3>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Compose and send a new SMS message</p>
                </div>

                <form action="{{ route('admin.messages.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Recipient Phone Number
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="024XXXXXXX"
                                   pattern="[0-9]{10,15}"
                                   minlength="10"
                                   maxlength="10"
                                   required
                                   class="pl-10 block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Enter 10 digit phone number (numbers only, e.g., 0242123456)</p>
                    </div>

                    <!-- Sender ID -->
                    <div>
                        <label for="sms_sender_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Sender ID <span class="text-red-500">*</span>
                        </label>
                        <select id="sms_sender_id" name="sms_sender_id" required class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">-- Select Sender ID --</option>
                            @foreach($senderIds as $sid)
                                <option value="{{ $sid->id }}" {{ old('sms_sender_id') == $sid->id ? 'selected' : '' }}>
                                    {{ $sid->sender_id }} ({{ $sid->user->name ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('sms_sender_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <!-- Message -->
                    <div x-data="{ messageLength: {{ old('message') ? strlen(old('message')) : 0 }} }">
                        <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Message Content
                        </label>
                        <textarea name="message" 
                                  id="message" 
                                  rows="6"
                                  required
                                  minlength="1"
                                  maxlength="160"
                                  @input="messageLength = $el.value.length"
                                  placeholder="Enter your message here..."
                                  class="block w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
                        <div class="mt-1 flex justify-between text-xs">
                            <span class="text-slate-500 dark:text-slate-400">SMS messages are limited to 160 characters (minimum 1 character)</span>
                            <span class="text-slate-500 dark:text-slate-400" :class="{ 'text-red-600 dark:text-red-400': messageLength > 160 }" x-text="messageLength + ' / 160'">0 / 160</span>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="space-y-4">
                        <!-- Single Message Info -->
                        <div class="rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-400">Single Message</h3>
                                    <div class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>This form sends messages to <strong>one recipient</strong></li>
                                            <li>Messages are <strong>queued</strong> and processed by background jobs</li>
                                            <li>Delivery status updates automatically</li>
                                            <li>Standard SMS rates apply</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Messaging Info -->
                        <div class="rounded-lg bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-purple-800 dark:text-purple-400">Need to Send Bulk Messages?</h3>
                                    <div class="mt-1 text-sm text-purple-700 dark:text-purple-300">
                                        <p class="mb-2">Use <strong>Campaigns</strong> to send messages to multiple recipients:</p>
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>📊 <strong>Upload Excel file</strong> with contacts</li>
                                            <li>👥 <strong>Select contact groups</strong></li>
                                            <li>📅 <strong>Schedule sending</strong> for later</li>
                                            <li>📈 <strong>Track campaign analytics</strong></li>
                                        </ul>
                                        <a href="{{ route('admin.campaigns.create') }}" 
                                           class="inline-flex items-center mt-3 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                            </svg>
                                            Create Campaign Instead
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                        <a href="{{ route('admin.messages.index') }}" 
                           class="px-4 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-50 dark:hover:bg-slate-600 transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                            <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Character counter for message textarea
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        
        if (messageTextarea && charCount) {
            messageTextarea.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = `${count} / 160`;
                
                if (count > 160) {
                    charCount.classList.add('text-red-600', 'dark:text-red-400');
                    charCount.classList.remove('text-slate-500', 'dark:text-slate-400');
                } else {
                    charCount.classList.remove('text-red-600', 'dark:text-red-400');
                    charCount.classList.add('text-slate-500', 'dark:text-slate-400');
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
