<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Upload Details</h2>
            <a href="{{ route('admin.uploads.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Uploads
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Upload Info Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Upload Information</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">File Name</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $upload->original_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">File Type</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ strtoupper($upload->file_type) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Uploaded By</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $upload->user->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $upload->user->email ?? '' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Upload Date</label>
                            <p class="mt-1 text-sm text-slate-900 dark:text-slate-100">{{ $upload->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $upload->created_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</label>
                            <div class="mt-1">
                                @if($upload->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        Completed
                                    </span>
                                @elseif($upload->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                        Failed
                                    </span>
                                @elseif($upload->status === 'processing')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400">
                                        Processing
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-slate-500 dark:text-slate-400">UUID</label>
                            <p class="mt-1 text-xs text-slate-900 dark:text-slate-100 font-mono">{{ $upload->uuid }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Rows</p>
                            <p class="text-3xl font-bold text-slate-900 dark:text-slate-100 mt-2">{{ number_format($upload->total_rows) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Processed</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($upload->processed_rows) }}</p>
                            @if($upload->total_rows > 0)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ number_format(($upload->processed_rows / $upload->total_rows) * 100, 1) }}%</p>
                            @endif
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Failed</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($upload->failed_rows) }}</p>
                            @if($upload->total_rows > 0)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ number_format(($upload->failed_rows / $upload->total_rows) * 100, 1) }}%</p>
                            @endif
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            @if($upload->total_rows > 0)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">Processing Progress</h3>
                <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-4">
                    <div class="bg-indigo-600 dark:bg-indigo-500 h-4 rounded-full flex items-center justify-end pr-2" 
                         style="width: {{ ($upload->processed_rows / $upload->total_rows) * 100 }}%">
                        <span class="text-xs font-semibold text-white">{{ number_format(($upload->processed_rows / $upload->total_rows) * 100, 1) }}%</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Error Log -->
            @if($upload->error_log && count($upload->error_log) > 0)
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-red-200 dark:border-red-800">
                <div class="p-6 border-b border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-400">Error Log</h3>
                </div>
                <div class="p-6">
                    <ul class="space-y-2">
                        @foreach($upload->error_log as $error)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-slate-900 dark:text-slate-100">{{ is_array($error) ? json_encode($error) : $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Imported Contacts -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Imported Contacts ({{ $upload->contacts->count() }})</h3>
                </div>
                @if($upload->contacts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Group</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                                @foreach($upload->contacts->take(50) as $contact)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $contact->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $contact->phone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 dark:text-slate-100">{{ $contact->contactGroup->name ?? 'No Group' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">{{ $contact->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($upload->contacts->count() > 50)
                        <div class="p-4 bg-slate-50 dark:bg-slate-900 text-center text-sm text-slate-600 dark:text-slate-400">
                            Showing 50 of {{ $upload->contacts->count() }} contacts
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <p class="text-sm text-slate-500 dark:text-slate-400">No contacts imported yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
