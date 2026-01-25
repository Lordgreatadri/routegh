<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
            Contact Messages
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters and Search -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 mb-6 border border-slate-200 dark:border-slate-700">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <!-- Status Filter Tabs -->
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.contact-messages.index') }}" 
                           class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            All ({{ array_sum($counts) }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('status') === 'new' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            New ({{ $counts['new'] }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('status') === 'read' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            Read ({{ $counts['read'] }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('status') === 'replied' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            Replied ({{ $counts['replied'] }})
                        </a>
                        <a href="{{ route('admin.contact-messages.index', ['status' => 'archived']) }}" 
                           class="px-4 py-2 rounded-lg {{ request('status') === 'archived' ? 'bg-blue-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600' }}">
                            Archived ({{ $counts['archived'] }})
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" class="flex gap-2">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search messages..." 
                               class="px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin.contact-messages.index', request()->except('search')) }}" 
                               class="px-4 py-2 bg-slate-500 hover:bg-slate-600 text-white rounded-lg">
                                Clear
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Bulk Delete -->
                <div class="mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <form method="POST" action="{{ route('admin.contact-messages.bulk-delete') }}" 
                          onsubmit="return confirm('Are you sure you want to delete all messages before this date?')" 
                          class="flex items-center gap-4">
                        @csrf
                        @method('DELETE')
                        <label class="text-sm text-slate-600 dark:text-slate-400">Delete messages before:</label>
                        <input type="date" 
                               name="before_date" 
                               required 
                               class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100">
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Delete Old Messages
                        </button>
                    </form>
                </div>
            </div>

            <!-- Messages List -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                @if($messages->count() > 0)
                    <div class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach($messages as $message)
                            <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                               class="text-lg font-semibold text-slate-900 dark:text-slate-100 hover:text-blue-600 dark:hover:text-blue-400">
                                                {{ $message->subject }}
                                            </a>
                                            @if($message->status === 'new')
                                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs font-medium rounded-full">
                                                    New
                                                </span>
                                            @elseif($message->status === 'read')
                                                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-xs font-medium rounded-full">
                                                    Read
                                                </span>
                                            @elseif($message->status === 'replied')
                                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">
                                                    Replied
                                                </span>
                                            @else
                                                <span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-400 text-xs font-medium rounded-full">
                                                    Archived
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400 mb-2">
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $message->name }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $message->email }}
                                            </span>
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $message->phone }}
                                            </span>
                                        </div>

                                        <p class="text-slate-700 dark:text-slate-300 mb-2 overflow-hidden">
                                            <span class="line-clamp-2">{{ $message->message }}</span>
                                            @if(strlen($message->message) > 150)
                                                <span class="text-blue-600 dark:text-blue-400 text-sm">... read more</span>
                                            @endif
                                        </p>

                                        <p class="text-xs text-slate-500 dark:text-slate-500">
                                            Received {{ $message->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="ml-4 flex items-center gap-2">
                                        <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                           class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">
                                            View
                                        </a>
                                        <form method="POST" 
                                              action="{{ route('admin.contact-messages.destroy', $message) }}" 
                                              onsubmit="return confirm('Are you sure you want to delete this message?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded-lg">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="p-6 border-t border-slate-200 dark:border-slate-700">
                        {{ $messages->withQueryString()->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="mt-4 text-slate-600 dark:text-slate-400">No contact messages found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
