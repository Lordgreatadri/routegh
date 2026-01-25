<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">My Support Messages</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium">Your Contact Messages</h3>
                    <a href="{{ route('contact.index') }}" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">New Message</a>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-500/10 border border-green-500/50 rounded-lg text-green-300">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Subject</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Submitted</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase tracking-wider">Reply</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            @forelse($messages as $message)
                            <tr class="hover:bg-slate-700/30 transition">
                                <td class="px-4 py-3">
                                    <div class="font-medium">{{ $message->subject }}</div>
                                    <div class="text-sm text-slate-400 truncate max-w-md">{{ Str::limit($message->message, 60) }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    @if($message->status === 'new')
                                        <span class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-300">New</span>
                                    @elseif($message->status === 'read')
                                        <span class="px-2 py-1 text-xs rounded bg-yellow-900 text-yellow-300">Read</span>
                                    @elseif($message->status === 'replied')
                                        <span class="px-2 py-1 text-xs rounded bg-green-900 text-green-300">Replied</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded bg-slate-700 text-slate-300">{{ ucfirst($message->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">{{ $message->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3">
                                    @if($message->admin_reply)
                                        <span class="flex items-center text-green-400 text-sm">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Yes
                                        </span>
                                    @else
                                        <span class="text-slate-500 text-sm">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('my-messages.show', $message->uuid) }}" class="px-3 py-1 bg-slate-700 hover:bg-slate-600 rounded text-sm">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="px-4 py-6 text-center text-slate-400" colspan="5">
                                    <div class="py-8">
                                        <svg class="mx-auto h-12 w-12 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                        <p class="mt-4">No messages yet.</p>
                                        <a href="{{ route('contact.index') }}" class="mt-2 inline-block text-indigo-400 hover:text-indigo-300">Send your first message →</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    @if($messages->hasPages())
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Showing <span class="font-medium">{{ $messages->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $messages->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $messages->total() }}</span> messages
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <label for="per_page" class="text-sm text-slate-600 dark:text-slate-400">Per page:</label>
                                    <select id="per_page" onchange="window.location.href = '{{ route('my-messages.index') }}?per_page=' + this.value" class="rounded border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-sm px-2 py-1">
                                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                {{ $messages->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
