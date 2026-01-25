<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">My Campaigns</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-4 rounded shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-slate-300">Total Campaigns</div>
                    <div class="text-2xl font-semibold">{{ auth()->user()->smsCampaigns()->count() }}</div>
                </div>
                <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-4 rounded shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-slate-300">Recent Messages</div>
                    <div class="text-2xl font-semibold">{{ \App\Models\SmsMessage::where('user_id', auth()->id())->latest()->limit(5)->count() }}</div>
                </div>
                <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-4 rounded shadow-sm">
                    <div class="text-sm text-gray-500 dark:text-slate-300">Active Campaigns</div>
                    <div class="text-2xl font-semibold">{{ auth()->user()->smsCampaigns()->where('status','processing')->count() }}</div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">Campaigns</h3>
                    <a href="{{ route('campaigns.create') }}" class="px-3 py-2 bg-indigo-600 text-white rounded">New Campaign</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Title</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Recipients</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Progress</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            @forelse($campaigns as $campaign)
                            @php
                                $pending = $campaign->smsMessages()->where('status','queued')->count();
                                $total = $campaign->total_recipients ?: ($pending + $campaign->smsMessages()->where('status','!=','queued')->count());
                                $processed = max(0, $total - $pending);
                                $percent = $total > 0 ? (int) floor(($processed / $total) * 100) : 0;
                            @endphp
                            <tr>
                                <td class="px-4 py-3">{{ $campaign->title ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $total }}</td>
                                <td class="px-4 py-3 w-48">
                                    <div class="text-sm mb-1">{{ $processed }} / {{ $total }} ({{ $percent }}%)</div>
                                    <div class="w-full bg-gray-200 h-2 rounded overflow-hidden dark:bg-slate-700">
                                        <div class="bg-indigo-600 h-2" style="width: {{ $percent }}%"></div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">{{ ucfirst($campaign->status) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('campaigns.show', $campaign) }}" class="px-2 py-1 bg-gray-200 rounded dark:bg-slate-700">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-4 py-6 text-center" colspan="5">No campaigns yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    @if($campaigns->hasPages())
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <!-- Pagination info -->
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Showing <span class="font-medium">{{ $campaigns->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $campaigns->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $campaigns->total() }}</span> campaigns
                            </div>

                            <!-- Pagination links -->
                            <div class="flex items-center gap-4">
                                <!-- Per page selector -->
                                <div class="flex items-center gap-2">
                                    <label for="per_page" class="text-sm text-slate-600 dark:text-slate-400">Per page:</label>
                                    <select id="per_page" onchange="window.location.href = '{{ route('campaigns.index') }}?per_page=' + this.value" class="rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm px-2 py-1">
                                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>

                                {{ $campaigns->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
