<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            System Logs Viewer
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 mb-6 border border-slate-200 dark:border-slate-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Log Type Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.logs.index', ['type' => 'laravel', 'filter' => request('filter', 'error'), 'limit' => request('limit', 100)]) }}" 
                           class="px-4 py-2 rounded-lg {{ $logType === 'laravel' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            Application Logs
                        </a>
                        <a href="{{ route('admin.logs.index', ['type' => 'sms', 'filter' => request('filter', 'error'), 'limit' => request('limit', 100)]) }}" 
                           class="px-4 py-2 rounded-lg {{ $logType === 'sms' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            SMS Logs
                        </a>
                    </div>

                    <!-- Filter & Limit Controls -->
                    <form method="GET" class="flex flex-wrap gap-2">
                        <input type="hidden" name="type" value="{{ $logType }}">
                        
                        @if($logType === 'laravel')
                            <select name="filter" 
                                    onchange="this.form.submit()"
                                    class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                                <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Levels</option>
                                <option value="error" {{ $filter === 'error' ? 'selected' : '' }}>Errors Only</option>
                                <option value="warning" {{ $filter === 'warning' ? 'selected' : '' }}>Warnings Only</option>
                                <option value="info" {{ $filter === 'info' ? 'selected' : '' }}>Info Only</option>
                            </select>
                        @endif
                        
                        <select name="limit" 
                                onchange="this.form.submit()"
                                class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                            <option value="50" {{ $limit === 50 ? 'selected' : '' }}>50 entries</option>
                            <option value="100" {{ $limit === 100 ? 'selected' : '' }}>100 entries</option>
                            <option value="200" {{ $limit === 200 ? 'selected' : '' }}>200 entries</option>
                            <option value="500" {{ $limit === 500 ? 'selected' : '' }}>500 entries</option>
                        </select>
                    </form>
                </div>

                <!-- Clear Logs Button -->
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <form method="POST" 
                          action="{{ route('admin.logs.clear') }}" 
                          onsubmit="return confirm('Are you sure you want to clear all {{ $logType }} logs? This action cannot be undone!')">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="type" value="{{ $logType }}">
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Clear {{ ucfirst($logType) }} Logs
                        </button>
                    </form>
                </div>
            </div>

            <!-- Logs Display -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                @if(count($logs) > 0)
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($logs as $log)
                            <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" x-data="{ expanded: false }">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Log Header -->
                                        <div class="flex items-center gap-3 mb-2">
                                            @if($log['level'] === 'error')
                                                <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs font-medium rounded-full uppercase">
                                                    Error
                                                </span>
                                            @elseif($log['level'] === 'warning')
                                                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-medium rounded-full uppercase">
                                                    Warning
                                                </span>
                                            @elseif($log['level'] === 'info')
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full uppercase">
                                                    Info
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-400 text-xs font-medium rounded-full uppercase">
                                                    {{ $log['level'] }}
                                                </span>
                                            @endif
                                            
                                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ $log['timestamp'] }}
                                            </span>
                                        </div>

                                        <!-- Log Message -->
                                        <div class="mb-2">
                                            <p class="text-slate-900 dark:text-slate-100 font-mono text-sm break-words">
                                                {{ $log['message'] }}
                                            </p>
                                        </div>

                                        <!-- File and Line -->
                                        @if($log['file'])
                                            <div class="text-sm text-slate-600 dark:text-slate-400 mb-2">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <span class="font-mono">{{ $log['file'] }}</span>
                                                @if(isset($log['line']) && $log['line'])
                                                    <span class="text-blue-600 dark:text-blue-400">:{{ $log['line'] }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Stack Trace Toggle -->
                                        @if(!empty($log['trace']))
                                            <button @click="expanded = !expanded" 
                                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline flex items-center gap-1">
                                                <svg x-show="!expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                                <svg x-show="expanded" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                                </svg>
                                                <span x-text="expanded ? 'Hide' : 'Show'"></span> Stack Trace
                                            </button>
                                            
                                            <!-- Stack Trace Content -->
                                            <div x-show="expanded" 
                                                 x-transition
                                                 class="mt-3 bg-slate-900 dark:bg-slate-950 rounded-lg p-4 overflow-x-auto">
                                                <pre class="text-xs text-slate-300 font-mono whitespace-pre-wrap">@foreach($log['trace'] as $traceLine)
{{ $traceLine }}
@endforeach</pre>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-4 text-slate-600 dark:text-slate-400">No {{ $logType }} logs found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
