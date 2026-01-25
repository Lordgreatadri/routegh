<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">
                Contact Message Details
            </h2>
            <a href="{{ route('admin.contact-messages.index') }}" 
               class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg">
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Message Details Card -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 mb-6 border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-200 dark:border-slate-700">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900 dark:text-slate-100 mb-2">
                            {{ $contactMessage->subject }}
                        </h3>
                        <div class="flex items-center gap-4 text-sm text-slate-600 dark:text-slate-400">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $contactMessage->created_at->format('M d, Y \a\t h:i A') }}
                            </span>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    <div>
                        @if($contactMessage->status === 'new')
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-sm font-medium rounded-full">
                                New
                            </span>
                        @elseif($contactMessage->status === 'read')
                            <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 text-sm font-medium rounded-full">
                                Read
                            </span>
                        @elseif($contactMessage->status === 'replied')
                            <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-sm font-medium rounded-full">
                                Replied
                            </span>
                        @else
                            <span class="px-3 py-1 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-400 text-sm font-medium rounded-full">
                                Archived
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Name</p>
                        <p class="text-slate-900 dark:text-slate-100 font-medium">{{ $contactMessage->name }}</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Email</p>
                        <a href="mailto:{{ $contactMessage->email }}" 
                           class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                            {{ $contactMessage->email }}
                        </a>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Phone</p>
                        <a href="tel:{{ $contactMessage->phone }}" 
                           class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                            {{ $contactMessage->phone }}
                        </a>
                    </div>
                </div>

                <!-- Message Content -->
                <div class="mb-6">
                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Message</h4>
                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4">
                        <p class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $contactMessage->message }}</p>
                    </div>
                </div>

                <!-- Admin Reply (if exists) -->
                @if($contactMessage->admin_reply)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Your Reply</h4>
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border-l-4 border-green-500">
                            <p class="text-slate-900 dark:text-slate-100 whitespace-pre-wrap">{{ $contactMessage->admin_reply }}</p>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                    <!-- Status Updates -->
                    @if($contactMessage->status !== 'archived')
                        <form method="POST" action="{{ route('admin.contact-messages.update-status', $contactMessage) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="archived">
                            <button type="submit" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg">
                                Archive
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.contact-messages.update-status', $contactMessage) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="read">
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Unarchive
                            </button>
                        </form>
                    @endif

                    <form method="POST" 
                          action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" 
                          onsubmit="return confirm('Are you sure you want to delete this message?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Delete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reply Form -->
            @if(!$contactMessage->admin_reply || $contactMessage->status !== 'replied')
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-6 border border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">
                        {{ $contactMessage->admin_reply ? 'Update Reply' : 'Send Reply' }}
                    </h3>

                    <form method="POST" action="{{ route('admin.contact-messages.reply', $contactMessage) }}">
                        @csrf
                        <div class="mb-4">
                            <label for="admin_reply" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Your Reply
                            </label>
                            <textarea id="admin_reply" 
                                      name="admin_reply" 
                                      rows="6" 
                                      required
                                      class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500"
                                      placeholder="Type your reply here...">{{ old('admin_reply', $contactMessage->admin_reply) }}</textarea>
                            @error('admin_reply')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Send Reply
                            </button>
                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Reply will be stored (email notification feature coming soon)
                            </p>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
