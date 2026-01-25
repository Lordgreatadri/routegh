<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Create SMS Campaign</h2>
            <a href="{{ route('admin.campaigns.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Campaigns
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <form action="{{ route('admin.campaigns.store') }}" method="POST" x-data="{ messageLength: {{ strlen(old('message', '')) }} }">
                    @csrf

                    <!-- Form Header -->
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">New SMS Campaign</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Send messages to your contacts</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6 space-y-6">
                        <!-- Campaign Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Campaign Title <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                </div>
                                <input type="text" 
                                       id="title" 
                                       name="title" 
                                       value="{{ old('title') }}"
                                       required
                                       maxlength="255"
                                       class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Enter campaign title (e.g., Holiday Promotion)" />
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Internal name to identify this campaign
                            </p>
                        </div>

                        <!-- Target Audience -->
                        <div>
                            <label for="contact_group_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Target Audience
                            </label>
                            <select id="contact_group_id" 
                                    name="contact_group_id" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">All Contacts</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ old('contact_group_id') == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} ({{ $group->contacts_count }} contacts)
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Select a specific group or send to all contacts
                            </p>
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
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Message <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea id="message" 
                                          name="message" 
                                          rows="5"
                                          required
                                          maxlength="300"
                                          @input="messageLength = $event.target.value.length"
                                          class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Enter your SMS message...">{{ old('message') }}</textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-slate-500 dark:text-slate-400">
                                    <span x-text="messageLength"></span>/300
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Keep your message clear and concise
                            </p>
                        </div>

                        <!-- Schedule (Optional) -->
                        <div>
                            <label for="scheduled_at" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Schedule Send Time (Optional)
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <input type="datetime-local" 
                                       id="scheduled_at" 
                                       name="scheduled_at" 
                                       value="{{ old('scheduled_at') }}"
                                       min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Leave empty to send immediately, or schedule for later
                            </p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-400 mb-1">About SMS Campaigns</h4>
                                    <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-1">
                                        <li>• Messages are sent to all contacts in the selected group</li>
                                        <li>• Maximum message length is 300 characters</li>
                                        <li>• You can schedule campaigns to send later</li>
                                        <li>• Campaign status can be tracked in the campaigns list</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 rounded-b-lg flex items-center justify-between">
                        <a href="{{ route('admin.campaigns.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Create Campaign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
