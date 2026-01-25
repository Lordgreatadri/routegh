<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">SMS Messages</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <div class="flex items-center justify-between mb-4 flex-col sm:flex-row gap-2">
                    <h3 class="text-lg font-medium">SMS Messages</h3>
                    <div class="flex gap-2 items-center flex-wrap">
                        <form method="GET" action="{{ route('sms-messages.index') }}" class="flex gap-2 items-center flex-wrap">
                            <input name="q" value="{{ request('q') }}" placeholder="Search phone" class="px-2 py-1 rounded border dark:bg-slate-900 dark:border-slate-700 text-sm" />
                            <select name="status" class="px-2 py-1 rounded border dark:bg-slate-900 dark:border-slate-700 text-sm">
                                <option value="">All Status</option>
                                <option value="queued"{{ request('status')=='queued'?' selected':'' }}>Queued</option>
                                <option value="sent"{{ request('status')=='sent'?' selected':'' }}>Sent</option>
                                <option value="delivered"{{ request('status')=='delivered'?' selected':'' }}>Delivered</option>
                                <option value="failed"{{ request('status')=='failed'?' selected':'' }}>Failed</option>
                            </select>
                            <select name="per_page" class="px-2 py-1 rounded border dark:bg-slate-900 dark:border-slate-700 text-sm">
                                <option value="15"{{ request('per_page', 15)==15?' selected':'' }}>15 per page</option>
                                <option value="25"{{ request('per_page')==25?' selected':'' }}>25 per page</option>
                                <option value="50"{{ request('per_page')==50?' selected':'' }}>50 per page</option>
                                <option value="100"{{ request('per_page')==100?' selected':'' }}>100 per page</option>
                            </select>
                            <button type="submit" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded text-sm">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                        <thead class="bg-gray-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase">Phone</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-slate-300 uppercase">Sent</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                            @forelse($smsMessages as $m)
                            <tr>
                                <td class="px-4 py-3">{{ $m->phone ?? '—' }}</td>
                                <td class="px-4 py-3">{{ ucfirst($m->status) }}</td>
                                <td class="px-4 py-3">{{ $m->sent_at ? $m->sent_at->diffForHumans() : '—' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('sms-messages.show', $m) }}" class="px-2 py-1 bg-gray-200 rounded dark:bg-slate-700">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td class="px-4 py-6 text-center" colspan="4">No SMS messages yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info and Links -->
                @if($smsMessages->hasPages())
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700 dark:text-slate-400">
                        Showing <span class="font-medium">{{ $smsMessages->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium">{{ $smsMessages->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium">{{ $smsMessages->total() }}</span> messages
                    </div>
                    <div class="dark:text-slate-300">
                        {{ $smsMessages->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
