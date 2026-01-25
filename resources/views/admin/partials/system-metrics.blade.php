<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
    <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">System Metrics</h4>
    
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Uploads</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $recentUploads->count() }}</p>
                </div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide">Campaigns</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $recentCampaigns->count() }}</p>
                </div>
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Uploads -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Latest Uploads</h5>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
        </div>
        
        @if($recentUploads->count() > 0)
            <div class="space-y-2">
                @foreach($recentUploads as $up)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-8 h-8 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3 min-w-0">
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                    {{ $up->filename ?? 'Upload #' . $up->id }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $up->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p class="text-sm text-slate-500 dark:text-slate-400">No recent uploads</p>
            </div>
        @endif
    </div>

    <!-- Latest Campaigns -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h5 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Latest Campaigns</h5>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            </svg>
        </div>
        
        @if($recentCampaigns->count() > 0)
            <div class="space-y-2">
                @foreach($recentCampaigns as $c)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700/50 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <div class="flex items-center min-w-0 flex-1">
                            <div class="w-8 h-8 rounded bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                            </div>
                            <div class="ml-3 min-w-0">
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate">
                                    {{ $c->title ?? 'Campaign #' . $c->id }}
                                </p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                    {{ $c->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @if(isset($c->status))
                            <div class="ml-2">
                                @if($c->status === 'active')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        Active
                                    </span>
                                @elseif($c->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                        Done
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                        {{ ucfirst($c->status) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-6 bg-slate-50 dark:bg-slate-700/50 rounded-lg">
                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                </svg>
                <p class="text-sm text-slate-500 dark:text-slate-400">No recent campaigns</p>
            </div>
        @endif
    </div>
</div>
