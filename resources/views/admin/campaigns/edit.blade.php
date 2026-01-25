<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Edit Campaign</h2>
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
                <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" x-data="{ messageLength: {{ strlen(old('message', $campaign->message)) }} }">
                    @csrf
                    @method('PUT')

                    <!-- Form Header -->
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Campaign</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Update campaign details</p>
                                </div>
                            </div>
                            <div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                        'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                    ];
                                    $colorClass = $statusColors[$campaign->status] ?? 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-300';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorClass }}">
                                    {{ ucfirst($campaign->status) }}
                                </span>
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
                                       value="{{ old('title', $campaign->title) }}"
                                       required
                                       maxlength="255"
                                       class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                       placeholder="Enter campaign title" />
                            </div>
                        </div>

                        <!-- Target Audience -->
                        <div>
                            <label for="contact_group_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Target Audience
                            </label>
                            @php
                                $currentGroupId = old('contact_group_id', $campaign->metadata['contact_group_id'] ?? null);
                            @endphp
                            <select id="contact_group_id" 
                                    name="contact_group_id" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">All Contacts</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" {{ $currentGroupId == $group->id ? 'selected' : '' }}>
                                        {{ $group->name }} ({{ $group->contacts_count }} contacts)
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Changing the audience will recalculate the total recipients
                            </p>
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
                                          maxlength="1000"
                                          @input="messageLength = $event.target.value.length"
                                          class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                          placeholder="Enter your SMS message...">{{ old('message', $campaign->message) }}</textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-slate-500 dark:text-slate-400">
                                    <span x-text="messageLength"></span>/1000
                                </div>
                            </div>
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
                                       value="{{ old('scheduled_at', $campaign->scheduled_at?->format('Y-m-d\TH:i')) }}"
                                       min="{{ now()->addMinutes(5)->format('Y-m-d\TH:i') }}"
                                       class="w-full pl-10 pr-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent" />
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Leave empty to send immediately, or schedule for later
                            </p>
                        </div>

                        <!-- Campaign Info -->
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                            <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Campaign Information</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-slate-600 dark:text-slate-400">Campaign ID:</span>
                                    <span class="ml-2 font-mono text-slate-900 dark:text-slate-100">{{ $campaign->id }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-600 dark:text-slate-400">Recipients:</span>
                                    <span class="ml-2 text-slate-900 dark:text-slate-100">{{ number_format($campaign->total_recipients) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-600 dark:text-slate-400">Created:</span>
                                    <span class="ml-2 text-slate-900 dark:text-slate-100">{{ $campaign->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-600 dark:text-slate-400">Last Updated:</span>
                                    <span class="ml-2 text-slate-900 dark:text-slate-100">{{ $campaign->updated_at->diffForHumans() }}</span>
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
                            Update Campaign
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
