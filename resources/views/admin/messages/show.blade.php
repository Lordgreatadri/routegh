<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Message Details</h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.messages.edit', $message) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Message
                </a>
                <a href="{{ route('admin.messages.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Messages
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <!-- Message Header -->
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">SMS Message</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Id: {{ $message->uuid }}</p>
                            </div>
                        </div>
                        @if($message->status === 'sent')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Sent
                            </span>
                        @elseif($message->status === 'failed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Failed
                            </span>
                        @elseif($message->status === 'queued')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Queued
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-400">
                                {{ ucfirst($message->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Message Details -->
                <div class="p-6 space-y-6">
                    <!-- Recipient Information -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Recipient Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                                <label class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Phone Number</label>
                                <div class="flex items-center mt-2">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $message->phone }}</p>
                                </div>
                            </div>
                            @if($message->contact_id)
                            <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                                <label class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Contact</label>
                                <div class="flex items-center mt-2">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">Contact ID: {{ $message->contact_id }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Message Content -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Message Content</h4>
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                            <p class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap leading-relaxed">{{ $message->message }}</p>
                            <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <span class="text-xs text-slate-500 dark:text-slate-400">{{ strlen($message->message) }} characters</span>
                            </div>
                        </div>
                    </div>

                    <!-- Campaign & User Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($message->sms_campaign_id)
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Campaign</label>
                            <p class="text-sm text-slate-900 dark:text-slate-100 mt-2">Campaign ID: {{ $message->sms_campaign_id }}</p>
                        </div>
                        @else
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Campaign</label>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-2 italic">Individual message (not part of a campaign)</p>
                        </div>
                        @endif

                        @if($message->user)
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4">
                            <label class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase">Created By</label>
                            <div class="flex items-center mt-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold text-sm mr-2">
                                    {{ strtoupper(substr($message->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $message->user->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $message->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Timestamps -->
                    <div>
                        <h4 class="text-sm font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Timeline</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-32 text-sm font-medium text-slate-600 dark:text-slate-400">Created:</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-slate-500 dark:text-slate-400">({{ $message->created_at->diffForHumans() }})</span>
                                </div>
                            </div>
                            @if($message->updated_at && $message->updated_at != $message->created_at)
                            <div class="flex items-center">
                                <div class="w-32 text-sm font-medium text-slate-600 dark:text-slate-400">Last Updated:</div>
                                <div class="text-sm text-slate-900 dark:text-slate-100">
                                    {{ $message->updated_at->format('F j, Y \a\t g:i A') }}
                                    <span class="text-slate-500 dark:text-slate-400">({{ $message->updated_at->diffForHumans() }})</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 rounded-b-lg">
                    <div class="flex items-center justify-between">
                        <button type="button" 
                                @click="$dispatch('open-confirm', { action: '{{ route('admin.messages.destroy', $message) }}', method: 'DELETE', name: {{ Js::from($message->phone) }} })" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Message
                        </button>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.messages.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                                Cancel
                            </a>
                            <a href="{{ route('admin.messages.edit', $message) }}" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                Edit Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.confirm-modal')
</x-app-layout>
