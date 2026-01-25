<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">API Log Details</h2>
            <a href="{{ route('admin.api-logs.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Logs
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $requestData = json_decode($apiLog->request, true) ?? [];
            @endphp

            <!-- Log Info Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">API Call Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Log ID</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100 font-mono">{{ $apiLog->id }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Provider</label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400">
                                    {{ $apiLog->provider ?? 'Unknown' }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Date/Time</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $apiLog->created_at->format('M d, Y h:i:s A') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $apiLog->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Message ID</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $apiLog->sms_message_id }}</p>
                            @if($apiLog->smsMessage)
                                <a href="{{ route('admin.messages.show', $apiLog->smsMessage) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View Message</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Request Data -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Request Data</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Phone Number</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100 font-semibold">{{ $requestData['phone'] ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Message Length</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $requestData['message_length'] ?? 0 }} characters</p>
                        </div>
                    </div>
                    
                    @if($apiLog->request)
                        <div class="mt-6">
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Raw Request</label>
                            <div class="mt-2 p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                <pre class="text-xs text-slate-900 dark:text-slate-100 overflow-x-auto font-mono">{{ $apiLog->request }}</pre>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Response Data -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Response Data</h3>
                </div>
                <div class="p-6">
                    @if($apiLog->response)
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                            <pre class="text-sm text-slate-900 dark:text-slate-100 overflow-x-auto whitespace-pre-wrap font-mono">{{ $apiLog->response }}</pre>
                        </div>
                    @else
                        <p class="text-sm text-slate-500 dark:text-slate-400 italic">No response data</p>
                    @endif
                </div>
            </div>

            <!-- Related Message Info -->
            @if($apiLog->smsMessage)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Related SMS Message</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</label>
                            <p class="mt-1">
                                @if($apiLog->smsMessage->status === 'sent')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        Sent
                                    </span>
                                @elseif($apiLog->smsMessage->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                        Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                        {{ ucfirst($apiLog->smsMessage->status) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Recipient</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $apiLog->smsMessage->phone }}</p>
                            @if($apiLog->smsMessage->contact)
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ $apiLog->smsMessage->contact->name }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Message</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ Str::limit($apiLog->smsMessage->message, 100) }}</p>
                        </div>
                        @if($apiLog->smsMessage->smsCampaign)
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Campaign</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $apiLog->smsMessage->smsCampaign->title }}</p>
                            <a href="{{ route('admin.campaigns.show', $apiLog->smsMessage->smsCampaign) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">View Campaign</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
