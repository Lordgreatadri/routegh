@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <div class="bg-slate-800 text-slate-100 shadow rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Upload History</h2>
            <div class="text-sm text-slate-400">Showing recent uploads</div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="text-left text-sm text-slate-300">
                        <th class="px-3 py-2">Original Name</th>
                        <th class="px-3 py-2">Type</th>
                        <th class="px-3 py-2">Total</th>
                        <th class="px-3 py-2">Processed</th>
                        <th class="px-3 py-2">Failed</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Uploaded</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($uploads as $u)
                        <tr class="text-sm">
                            <td class="px-3 py-2">{{ $u->original_name }}</td>
                            <td class="px-3 py-2">{{ strtoupper($u->file_type) }}</td>
                            <td class="px-3 py-2">{{ $u->total_rows }}</td>
                            <td class="px-3 py-2">{{ $u->processed_rows }}</td>
                            <td class="px-3 py-2">{{ $u->failed_rows }}</td>
                            <td class="px-3 py-2">
                                @if($u->status === 'completed')
                                    <span class="text-green-400">Completed</span>
                                @elseif($u->status === 'processing')
                                    <span class="text-yellow-300">Processing</span>
                                @elseif($u->status === 'failed')
                                    <span class="text-red-400">Failed</span>
                                @else
                                    <span class="text-slate-400">Pending</span>
                                @endif
                            </td>
                            <td class="px-3 py-2">{{ $u->created_at->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-3 py-4 text-slate-400">No uploads yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            @if($uploads->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-slate-400">
                        Showing <span class="font-medium">{{ $uploads->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $uploads->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $uploads->total() }}</span> uploads
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-slate-400">Per page:</label>
                            <select id="per_page" onchange="window.location.href = '{{ route('uploads.index') }}?per_page=' + this.value" class="rounded border-slate-600 bg-slate-700 text-slate-200 text-sm px-2 py-1">
                                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        {{ $uploads->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
