<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">{{ $campaign->title }}</h2>
            <div class="flex items-center gap-3">
                @if(in_array($campaign->status, ['pending', 'scheduled']))
                <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Campaign
                </a>
                @endif
                <a href="{{ route('admin.campaigns.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Campaigns
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Campaign Details Card -->
            <div class="mb-6 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-start">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-10 h-10 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ $campaign->title }}</h3>
                            <div class="flex items-center gap-4 mt-2 text-sm text-slate-500 dark:text-slate-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    ID: {{ $campaign->id }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Created: {{ $campaign->created_at->format('M d, Y h:i A') }}
                                </div>
                                @if($campaign->user)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    By: {{ $campaign->user->name }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                'scheduled' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                'processing' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
                                'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                            ];
                            $colorClass = $statusColors[$campaign->status] ?? 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-300';
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $colorClass }}">
                            {{ ucfirst($campaign->status) }}
                        </span>
                    </div>
                </div>

                <!-- Message Preview -->
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Message</h4>
                    <div class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <p class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $campaign->message }}</p>
                    </div>
                </div>

                <!-- Campaign Stats -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                        <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">Total Recipients</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ number_format($campaign->total_recipients) }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">Total Messages</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-slate-100">{{ number_format($messageStats['total']) }}</p>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 border border-yellow-200 dark:border-yellow-800">
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mb-1">Queued</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ number_format($messageStats['queued']) }}</p>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                        <p class="text-sm text-green-600 dark:text-green-400 mb-1">Sent</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ number_format($messageStats['sent']) }}</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                        <p class="text-sm text-red-600 dark:text-red-400 mb-1">Failed</p>
                        <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ number_format($messageStats['failed']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Messages List -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Campaign Messages</h3>
                </div>

                @if($messages->count() > 0)
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Recipient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Sent At</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($messages as $message)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                    <td class="px-6 py-4">
                                        @if($message->contact)
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-medium">
                                                    {{ substr($message->contact->name ?? 'U', 0, 1) }}
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $message->contact->name }}</div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-slate-500 dark:text-slate-400">Unknown</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center text-sm text-slate-900 dark:text-slate-100">
                                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $message->phone }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $messageStatusColors = [
                                                'queued' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                                                'sent' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                                                'delivered' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                                                'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                            ];
                                            $msgColorClass = $messageStatusColors[$message->status] ?? 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-300';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $msgColorClass }}">
                                            {{ ucfirst($message->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($message->sent_at)
                                            <div class="text-sm text-slate-900 dark:text-slate-100">{{ $message->sent_at->diffForHumans() }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ $message->sent_at->format('M d, Y h:i A') }}</div>
                                        @else
                                            <span class="text-sm text-slate-500 dark:text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($message->smsApiLog)
                                        <button type="button" @click.prevent="$dispatch('open-modal', 'log-{{ $message->id }}')" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            View API Log
                                        </button>
                                        @else
                                        <span class="text-sm text-slate-500 dark:text-slate-400">No log</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($messages->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Showing <span class="font-medium text-slate-900 dark:text-slate-100">{{ $messages->firstItem() ?? 0 }}</span> to <span class="font-medium text-slate-900 dark:text-slate-100">{{ $messages->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-900 dark:text-slate-100">{{ $messages->total() }}</span> messages
                                </div>
                                <div>
                                    {{ $messages->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="px-6 py-12 text-center">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">No messages have been created for this campaign yet</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Messages will appear here once the campaign is processed</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- API Log Modals -->
    @foreach($messages as $m)
        @if($m->smsApiLog)
        <x-modal name="log-{{ $m->id }}" maxWidth="2xl">
            <div class="p-6 bg-white dark:bg-slate-800">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-slate-100">API Log Details</h3>
                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">{{ $m->phone }}</p>
                    </div>
                    <button type="button" @click="$dispatch('close')" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-5">
                    <div class="border-b border-gray-200 dark:border-slate-700 pb-4">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Provider</label>
                        <p class="text-base text-gray-900 dark:text-slate-100 font-medium">{{ $m->smsApiLog->provider ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="border-b border-gray-200 dark:border-slate-700 pb-4">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Request Payload</label>
                        <pre class="mt-2 p-4 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-xs overflow-x-auto text-gray-800 dark:text-slate-200 font-mono">{{ json_encode(json_decode($m->smsApiLog->request ?? '{}'), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                    
                    <div class="border-b border-gray-200 dark:border-slate-700 pb-4">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Provider Response</label>
                        <pre class="mt-2 p-4 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg text-xs overflow-x-auto text-gray-800 dark:text-slate-200 font-mono">{{ $m->smsApiLog->response ?? 'N/A' }}</pre>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Timestamp</label>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm text-gray-900 dark:text-slate-100 font-medium">{{ $m->smsApiLog->created_at->format('M d, Y - h:i:s A') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-slate-700">
                    <button type="button" @click="$dispatch('close')" class="px-5 py-2.5 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition shadow-sm">
                        Close
                    </button>
                </div>
            </div>
        </x-modal>
        @endif
    @endforeach
</x-app-layout>
