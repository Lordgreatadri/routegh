<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Support - Message Admin</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition
                     x-init="setTimeout(() => show = false, 3000)"
                     class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                        </div>
                        <button @click="show = false" class="text-green-600 dark:text-green-400 hover:text-green-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Total</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-slate-100 mt-1">{{ $stats['total_messages'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">From Admin</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">{{ $stats['admin_messages'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">From You</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['my_messages'] }}</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm p-4 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">New</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['unread_count'] }}</p>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 mb-4">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Conversation with Admin</h3>
                </div>
                
                <div class="p-6 h-[500px] overflow-y-auto space-y-4" id="messagesContainer">
                    @forelse($messages as $message)
                        <div class="flex {{ $message->is_from_admin ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[70%]">
                                <div class="flex items-center gap-2 mb-1 {{ $message->is_from_admin ? 'justify-start' : 'justify-end' }}">
                                    <span class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $message->is_from_admin ? ($message->admin ? $message->admin->name : 'Admin') : 'You' }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $message->created_at->format('M d, h:i A') }}</span>
                                </div>
                                <div class="rounded-lg px-4 py-2 {{ $message->is_from_admin ? 'bg-slate-100 dark:bg-slate-700 text-slate-900 dark:text-slate-100' : 'bg-indigo-600 text-white' }}">
                                    <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                                </div>
                                @if($message->is_from_admin)
                                    <div class="flex items-center gap-2 mt-1 text-xs">
                                        <span class="text-slate-500 dark:text-slate-400">
                                            {{ $message->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2 mt-1 text-xs justify-end">
                                        @if($message->status === 'unread')
                                            <span class="text-slate-400">● Sent</span>
                                        @elseif($message->status === 'read')
                                            <span class="text-blue-400">✓ Read</span>
                                        @elseif($message->status === 'replied')
                                            <span class="text-green-400">✓✓ Replied</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">No messages yet</p>
                            <p class="text-xs text-slate-400">Send your first message to admin below</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Send Message Form -->
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-4">
                <form method="POST" action="{{ route('user-messages.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                            Send Message to Admin
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="3" 
                                  required
                                  minlength="1"
                                  maxlength="1000"
                                  placeholder="Type your message to admin..."
                                  class="w-full rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Maximum 1000 characters</p>
                    </div>
                    
                    <div class="flex items-center justify-end">
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send to Admin
                        </button>
                    </div>
                </form>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">About Support Messages</h4>
                        <p class="text-sm text-blue-800 dark:text-blue-400">
                            This is your direct communication channel with the admin team. Send messages, questions, or support requests and the admin will respond here.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
    @endpush
</x-app-layout>
