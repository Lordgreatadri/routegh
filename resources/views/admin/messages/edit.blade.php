<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Edit Message</h2>
            <a href="{{ route('admin.messages.show', $message) }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Message
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <form action="{{ route('admin.messages.update', $message) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Form Header -->
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Edit Message Details</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Update the message content and status</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6 space-y-6">
                        <!-- Read-only Phone Number -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Phone Number
                            </label>
                            <div class="flex items-center bg-slate-100 dark:bg-slate-900 rounded-lg px-4 py-3">
                                <svg class="w-5 h-5 text-slate-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="text-slate-900 dark:text-slate-100 font-medium">{{ $message->phone }}</span>
                                <span class="ml-auto text-xs text-slate-500 dark:text-slate-400">Read-only</span>
                            </div>
                        </div>

                        <!-- Message Content -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Message Content <span class="text-red-500">*</span>
                            </label>
                            <textarea id="message" 
                                      name="message" 
                                      rows="5" 
                                      required
                                      maxlength="160"
                                      class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Enter your message here..."
                                      x-data="{ count: {{ strlen($message->message) }} }"
                                      @input="count = $el.value.length">{{ old('message', $message->message) }}</textarea>
                            <div class="flex items-center justify-between mt-2">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Standard SMS messages are limited to 160 characters</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400" x-text="`${count}/160 characters`"></p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Status
                            </label>
                            <select id="status" 
                                    name="status" 
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="queued" {{ old('status', $message->status) === 'queued' ? 'selected' : '' }}>Queued</option>
                                <option value="sent" {{ old('status', $message->status) === 'sent' ? 'selected' : '' }}>Sent</option>
                                <option value="failed" {{ old('status', $message->status) === 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Update the message status based on delivery outcome</p>
                        </div>

                        <!-- Info Box -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-400 mb-1">Note about editing messages</h4>
                                    <p class="text-xs text-blue-800 dark:text-blue-300">
                                        The phone number cannot be changed after the message is created. You can only update the message content and status.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 rounded-b-lg flex items-center justify-between">
                        <a href="{{ route('admin.messages.show', $message) }}" 
                           class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
