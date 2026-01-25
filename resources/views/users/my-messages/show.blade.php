<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Message Details</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('my-messages.index') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Messages
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 rounded-lg shadow-sm overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $contactMessage->subject }}</h3>
                            <p class="mt-2 text-indigo-100">Submitted {{ $contactMessage->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            @if($contactMessage->status === 'new')
                                <span class="px-3 py-1 text-sm rounded-full bg-blue-500 text-white">New</span>
                            @elseif($contactMessage->status === 'read')
                                <span class="px-3 py-1 text-sm rounded-full bg-yellow-500 text-white">Read</span>
                            @elseif($contactMessage->status === 'replied')
                                <span class="px-3 py-1 text-sm rounded-full bg-green-500 text-white">Replied</span>
                            @else
                                <span class="px-3 py-1 text-sm rounded-full bg-slate-500 text-white">{{ ucfirst($contactMessage->status) }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="border-b border-slate-700 px-6 py-4 bg-slate-900/50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-slate-400">Name:</span>
                            <span class="ml-2 font-medium">{{ $contactMessage->name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400">Email:</span>
                            <a href="mailto:{{ $contactMessage->email }}" class="ml-2 font-medium text-indigo-400 hover:text-indigo-300">{{ $contactMessage->email }}</a>
                        </div>
                        <div>
                            <span class="text-slate-400">Phone:</span>
                            <a href="tel:{{ $contactMessage->phone }}" class="ml-2 font-medium text-indigo-400 hover:text-indigo-300">{{ $contactMessage->phone }}</a>
                        </div>
                    </div>
                </div>

                <!-- Your Message -->
                <div class="px-6 py-6 border-b border-slate-700">
                    <h4 class="text-lg font-semibold mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Your Message
                    </h4>
                    <div class="bg-slate-900/30 rounded-lg p-4 text-slate-200 whitespace-pre-wrap">{{ $contactMessage->message }}</div>
                </div>

                <!-- Admin Reply -->
                <div class="px-6 py-6">
                    <h4 class="text-lg font-semibold mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Admin Reply
                    </h4>
                    @if($contactMessage->admin_reply)
                        <div class="bg-green-900/20 border border-green-500/30 rounded-lg p-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-green-300 mb-2">Support Team Response</p>
                                    <div class="text-slate-200 whitespace-pre-wrap">{{ $contactMessage->admin_reply }}</div>
                                    @if($contactMessage->read_at)
                                        <p class="mt-3 text-xs text-slate-400">Replied {{ $contactMessage->updated_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-900/30 border border-slate-700 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="mt-2 text-slate-400">No reply yet. Our support team will respond soon.</p>
                            <p class="mt-1 text-sm text-slate-500">You'll receive an email notification when we reply.</p>
                        </div>
                    @endif
                </div>

                <!-- Message Info -->
                <div class="bg-slate-900/50 px-6 py-4 border-t border-slate-700">
                    <div class="flex items-center justify-between text-xs text-slate-400">
                        <div>Message ID: {{ $contactMessage->uuid }}</div>
                        <div class="flex items-center space-x-4">
                            @if($contactMessage->read_at)
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Read {{ $contactMessage->read_at->diffForHumans() }}
                                </span>
                            @endif
                            <span>Updated {{ $contactMessage->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Actions -->
            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('my-messages.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    All Messages
                </a>
                <a href="{{ route('contact.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Message
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
