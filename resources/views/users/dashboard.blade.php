<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight tracking-tight">
            {{ __('User Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-100">
                    <!-- Main Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 bg-slate-700 rounded shadow-sm">
                            <h3 class="text-sm text-slate-300 uppercase tracking-wide">Total Messages</h3>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_messages']) }}</p>
                            <p class="text-xs text-slate-400 mt-1">Sent: {{ number_format($stats['messages_sent']) }} • Delivered: {{ number_format($stats['messages_delivered']) }}</p>
                        </div>

                        <div class="p-4 bg-slate-700 rounded shadow-sm">
                            <h3 class="text-sm text-slate-300 uppercase tracking-wide">Total Contacts</h3>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_contacts']) }}</p>
                            <p class="text-xs text-slate-400 mt-1">Groups: {{ number_format($stats['total_groups']) }}</p>
                        </div>

                        <div class="p-4 bg-slate-700 rounded shadow-sm">
                            <h3 class="text-sm text-slate-300 uppercase tracking-wide">Campaigns</h3>
                            <p class="text-3xl font-bold mt-2">{{ number_format($stats['total_campaigns']) }}</p>
                            <p class="text-xs text-slate-400 mt-1">Active: {{ number_format($stats['campaigns_active']) }} • Completed: {{ number_format($stats['campaigns_completed']) }}</p>
                        </div>

                        <div class="p-4 bg-slate-700 rounded shadow-sm">
                            <h3 class="text-sm text-slate-300 uppercase tracking-wide">Failed Messages</h3>
                            <p class="text-3xl font-bold mt-2 {{ $stats['messages_failed'] > 0 ? 'text-red-400' : '' }}">{{ number_format($stats['messages_failed']) }}</p>
                            <p class="text-xs text-slate-400 mt-1">Requires attention</p>
                        </div>
                    </div>

                    <!-- Recent Campaigns Section -->
                    <div class="mt-6">
                        <div class="bg-slate-900 p-4 rounded shadow-sm">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-slate-200 font-semibold text-lg">Recent Campaigns</h4>
                                <a href="{{ route('campaigns.index') }}" class="text-sm text-indigo-400 hover:text-indigo-300">View all →</a>
                            </div>
                            <div class="text-slate-300 text-sm">
                                @forelse($recentCampaigns as $campaign)
                                    <div class="py-3 border-b border-slate-700 last:border-0">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <a href="{{ route('campaigns.show', $campaign) }}" class="text-slate-100 hover:text-indigo-400 font-medium">{{ $campaign->title ?? 'Untitled Campaign' }}</a>
                                                <div class="text-xs text-slate-400 mt-1">
                                                    {{ $campaign->created_at->diffForHumans() }} • {{ $campaign->total_recipients ?? 0 }} recipients
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                @if($campaign->status === 'completed')
                                                    <span class="px-2 py-1 text-xs rounded bg-green-900 text-green-300">Completed</span>
                                                @elseif($campaign->status === 'processing')
                                                    <span class="px-2 py-1 text-xs rounded bg-yellow-900 text-yellow-300">Processing</span>
                                                @elseif($campaign->status === 'pending')
                                                    <span class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-300">Pending</span>
                                                @else
                                                    <span class="px-2 py-1 text-xs rounded bg-slate-700 text-slate-300">{{ ucfirst($campaign->status) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-slate-400 text-center py-8">
                                        <div class="mb-2">No campaigns yet.</div>
                                        <a href="{{ route('campaigns.create') }}" class="text-indigo-400 hover:text-indigo-300 text-sm">Create your first campaign →</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-5 gap-4">
                        <a href="{{ route('campaigns.create') }}" class="p-4 bg-indigo-600 hover:bg-indigo-700 rounded shadow-sm text-center transition">
                            <h5 class="text-sm font-semibold text-white">New Campaign</h5>
                            <p class="text-xs text-indigo-100 mt-1">Create a new SMS campaign</p>
                        </a>
                        <a href="{{ route('contacts.index') }}" class="p-4 bg-slate-700 hover:bg-slate-600 rounded shadow-sm text-center transition">
                            <h5 class="text-sm font-semibold text-white">Manage Contacts</h5>
                            <p class="text-xs text-slate-300 mt-1">View and organize contacts</p>
                        </a>
                        <a href="{{ route('users.sender-ids.index') }}" class="p-4 bg-slate-700 hover:bg-slate-600 rounded shadow-sm text-center transition">
                            <h5 class="text-sm font-semibold text-white">Sender IDs</h5>
                            <p class="text-xs text-slate-300 mt-1">Manage your Sender IDs</p>
                        </a>
                        <a href="{{ route('sms-messages.index') }}" class="p-4 bg-slate-700 hover:bg-slate-600 rounded shadow-sm text-center transition">
                            <h5 class="text-sm font-semibold text-white">View Messages</h5>
                            <p class="text-xs text-slate-300 mt-1">Check message history</p>
                        </a>
                        <a href="{{ route('my-messages.index') }}" class="p-4 bg-slate-700 hover:bg-slate-600 rounded shadow-sm text-center transition">
                            <h5 class="text-sm font-semibold text-white">Support Messages</h5>
                            <p class="text-xs text-slate-300 mt-1">View your inquiries</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
