<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Admin Dashboard</h2>
            <span class="text-sm text-slate-400">{{ now()->format('l, F j, Y') }}</span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Top Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Users -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Users</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $stats['total_users'] }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                <span class="text-green-600 dark:text-green-400">{{ $stats['approved_users'] }}</span> approved
                            </p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Pending Approvals</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $stats['pending_approvals'] }}</p>
                            @if($stats['pending_approvals'] > 0)
                                <a href="{{ route('admin.users.pending') }}" class="text-sm text-yellow-600 dark:text-yellow-400 hover:underline mt-1 inline-block">
                                    Review now →
                                </a>
                            @else
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">All caught up!</p>
                            @endif
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Messages -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Messages</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ number_format($stats['messages_total']) }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                <span class="text-green-600 dark:text-green-400">{{ $stats['messages_delivered'] }}</span> delivered
                                @if($stats['messages_failed'] > 0)
                                    · <span class="text-red-600 dark:text-red-400">{{ $stats['messages_failed'] }}</span> failed
                                @endif
                            </p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Campaigns -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Campaigns</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ $stats['campaigns_total'] }}</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                <span class="text-indigo-600 dark:text-indigo-400">{{ $stats['campaigns_active'] }}</span> active
                            </p>
                        </div>
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Contacts</p>
                            <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ number_format($stats['contacts_total']) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">Contact Groups</p>
                            <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ $stats['groups_total'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center">
                        <div class="p-2 bg-slate-100 dark:bg-slate-700 rounded">
                            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-slate-500 dark:text-slate-400">API Calls Today</p>
                            <p class="text-xl font-semibold text-slate-900 dark:text-slate-100">{{ number_format($stats['api_calls_today']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Metrics Section -->
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">System Metrics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <!-- Today's Messages -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Messages Sent Today</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ number_format($metricsData['today']['messages_sent']) }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    Last 7 days: <span class="text-blue-600 dark:text-blue-400">{{ number_format($metricsData['last_7_days']['messages_sent']) }}</span>
                                </p>
                            </div>
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Uploads -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Uploads Today</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ number_format($metricsData['today']['uploads']) }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    Last 7 days: <span class="text-green-600 dark:text-green-400">{{ number_format($metricsData['last_7_days']['uploads']) }}</span>
                                </p>
                            </div>
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Recipients -->
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Recipients Today</p>
                                <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ number_format($metricsData['today']['recipients']) }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                                    Last 7 days: <span class="text-purple-600 dark:text-purple-400">{{ number_format($metricsData['last_7_days']['recipients']) }}</span>
                                </p>
                            </div>
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
                <!-- Message Trend Chart -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Message Trends (Last 7 Days)</h3>
                    <canvas id="messageTrend" height="120"></canvas>
                </div>

                <!-- Message Queue Status -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">Message Queue</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">Queued</span>
                            </div>
                            <span class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $stats['messages_queued'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">Sent</span>
                            </div>
                            <span class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $stats['messages_sent'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">Delivered</span>
                            </div>
                            <span class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $stats['messages_delivered'] }}</span>
                        </div>
                        @if($stats['messages_failed'] > 0)
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                <span class="text-sm text-red-600 dark:text-red-400">Failed</span>
                            </div>
                            <span class="text-lg font-semibold text-red-600 dark:text-red-400">{{ $stats['messages_failed'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <!-- Recent Pending Users -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Pending User Approvals</h3>
                            @if($stats['pending_approvals'] > 0)
                                <a href="{{ route('admin.users.pending') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all →</a>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentPendingUsers->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentPendingUsers as $user)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                                        <div class="flex items-center min-w-0 flex-1">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-3 min-w-0 flex-1">
                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">{{ $user->name }}</p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                        <div class="ml-4 text-right">
                                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $user->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400">No pending approvals</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Campaigns -->
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Recent Campaigns</h3>
                            <a href="{{ route('admin.campaigns.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">View all →</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($recentCampaigns->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentCampaigns as $campaign)
                                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">{{ $campaign->title }}</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                Created {{ $campaign->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        <div class="ml-4">
                                            @if($campaign->status === 'active')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                                    Active
                                                </span>
                                            @elseif($campaign->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                                    Completed
                                                </span>
                                            @elseif($campaign->status === 'scheduled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                                    Scheduled
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-400">
                                                    {{ ucfirst($campaign->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400">No campaigns yet</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.confirm-modal')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        (function(){
            const labels = {!! Illuminate\Support\Js::from($trendLabels ?? []) !!};
            const data = {!! Illuminate\Support\Js::from($trendData ?? []) !!};
            const ctx = document.getElementById('messageTrend');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: { 
                        labels: labels, 
                        datasets: [{ 
                            label: 'Messages Sent', 
                            data: data, 
                            borderColor: '#6366F1', 
                            backgroundColor: 'rgba(99,102,241,0.1)', 
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#6366F1',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: { 
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#6366F1',
                                borderWidth: 1
                            }
                        }, 
                        scales: { 
                            y: { 
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(148, 163, 184, 0.1)'
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#94a3b8'
                                }
                            }
                        } 
                    }
                });
            }
        })();
    </script>
</x-app-layout>
