<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Campaign Details</h2>
    </x-slot>

    <div class="py-6" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-2xl font-semibold">{{ $campaign->title ?? 'Untitled' }}</h3>
                        <div class="text-sm text-gray-500 dark:text-slate-300">
                            Sender ID: {{ $campaign->sms_sender_id ? (\App\Models\SmsSenderId::find($campaign->sms_sender_id)?->sender_id ?? '—') : '—' }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-slate-300">{{ $campaign->created_at->diffForHumans() }} • {{ ucfirst($campaign->status) }}</div>
                    </div>
                    @php
                        $pending = $campaign->smsMessages()->where('status','queued')->count();
                        $total = $campaign->total_recipients ?: ($pending + $campaign->smsMessages()->where('status','!=','queued')->count());
                        $processed = max(0, $total - $pending);
                        $percent = $total > 0 ? (int) floor(($processed / $total) * 100) : 0;
                    @endphp
                    <div class="w-full md:w-1/3">
                        <div class="text-sm mb-1">Progress: {{ $processed }} / {{ $total }} ({{ $percent }}%)</div>
                        <div class="w-full bg-gray-200 h-3 rounded overflow-hidden dark:bg-slate-700">
                            <div class="bg-indigo-600 h-3" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                        <div class="text-xs text-gray-500 dark:text-slate-300">Recipients</div>
                        <div class="text-xl font-medium">{{ $total }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                        <div class="text-xs text-gray-500 dark:text-slate-300">Sent / Delivered</div>
                        <div class="text-xl font-medium">{{ $campaign->successfulSendsCount() }} / {{ $campaign->deliveredCount() }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                        <div class="text-xs text-gray-500 dark:text-slate-300">Failed</div>
                        <div class="text-xl font-medium">{{ $campaign->failedSendsCount() }}</div>
                    </div>
                </div>

                <div class="mt-6">
                    <h4 class="text-lg font-medium mb-2">Messages</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                            <thead class="bg-gray-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Phone</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Sent At</th>
                                    <th class="px-4 py-2">API Log</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                                @foreach($messages as $m)
                                <tr>
                                    <td class="px-4 py-3">{{ $m->phone }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($m->status) }}</td>
                                    <td class="px-4 py-3">{{ $m->sent_at ? $m->sent_at->diffForHumans() : '—' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        @if($m->smsApiLog)
                                        <button type="button" @click.prevent="$dispatch('open-modal', 'log-{{ $m->id }}')" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 rounded dark:bg-slate-700 dark:hover:bg-slate-600 transition">
                                            View API Log
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        @if($messages->hasPages())
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="text-sm text-slate-600 dark:text-slate-400">
                                    Showing <span class="font-medium">{{ $messages->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $messages->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $messages->total() }}</span> messages
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2">
                                        <label for="per_page" class="text-sm text-slate-600 dark:text-slate-400">Per page:</label>
                                        <select id="per_page" onchange="window.location.href = '{{ route('campaigns.show', $campaign) }}?per_page=' + this.value" class="rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm px-2 py-1">
                                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                            <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                    </div>
                                    {{ $messages->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
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
