<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Pending User Approvals</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Review and approve new user registrations</p>
            </div>
            @if($users->count() > 0)
                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400">
                    {{ $users->total() }} Pending
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6" x-data="{}">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                    <p class="text-sm text-green-800 dark:text-green-400">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <p class="text-sm text-red-800 dark:text-red-400">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                        <thead class="bg-slate-50 dark:bg-slate-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($users as $user)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">ID: {{ $user->uuid }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-slate-900 dark:text-slate-100">{{ $user->email }}</div>
                                    @if($user->phone)
                                        <div class="text-xs text-slate-500 dark:text-slate-400">{{ $user->phone }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-slate-900 dark:text-slate-100">{{ $user->created_at->diffForHumans() }}</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ $user->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                                @click="$dispatch('open-confirm', { action: '{{ route('admin.users.approve', $user) }}', method: 'POST', name: {{ Js::from($user->name) }}, description: 'Are you sure you want to approve this user?' })" 
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-medium transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Approve
                                        </button>
                                        <button type="button" 
                                                @click="$dispatch('open-confirm', { action: '{{ route('admin.users.reject', $user) }}', method: 'POST', name: {{ Js::from($user->name) }}, description: 'Are you sure you want to reject this user?' })" 
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No pending users found</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">All user registrations have been processed</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900">
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="text-sm text-slate-600 dark:text-slate-400">
                                Showing <span class="font-medium text-slate-900 dark:text-slate-100">{{ $users->firstItem() ?? 0 }}</span> to <span class="font-medium text-slate-900 dark:text-slate-100">{{ $users->lastItem() ?? 0 }}</span> of <span class="font-medium text-slate-900 dark:text-slate-100">{{ $users->total() }}</span> pending users
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <label for="per_page" class="text-sm text-slate-600 dark:text-slate-400">Per page:</label>
                                    <select id="per_page" 
                                            onchange="window.location.href = '{{ route('admin.users.pending') }}?per_page=' + this.value" 
                                            class="rounded-lg border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @include('components.confirm-modal')
</x-app-layout>
