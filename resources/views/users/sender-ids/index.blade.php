
@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-4xl">
    <h2 class="text-2xl font-bold mb-4 text-white">Manage Sender IDs</h2>
    <a href="{{ route('users.sender-ids.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">Add Sender ID</a>
    <div class="bg-slate-800 shadow rounded p-4">
        <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Sender ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Description</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Approval</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                @forelse($senderIds as $senderId)
                <tr>
                    <td class="px-4 py-3 text-slate-100 font-semibold">{{ $senderId->sender_id }}</td>
                    <td class="px-4 py-3 text-slate-300">{{ $senderId->description }}</td>
                    <td class="px-4 py-3">
                        @if($senderId->status === 'active')
                            <span class="px-2 py-1 rounded text-xs bg-green-900 text-green-300">Active</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-slate-700 text-slate-300">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($senderId->approval_status === 'approved')
                            <span class="px-2 py-1 rounded text-xs bg-green-900 text-green-300">Approved</span>
                        @elseif($senderId->approval_status === 'pending')
                            <span class="px-2 py-1 rounded text-xs bg-yellow-900 text-yellow-300">Pending</span>
                        @else
                            <span class="px-2 py-1 rounded text-xs bg-red-900 text-red-300">Rejected</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('users.sender-ids.edit', $senderId) }}" class="text-blue-400 hover:underline">Edit</a>
                        <form action="{{ route('users.sender-ids.destroy', $senderId) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 ml-2 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-slate-400">No sender IDs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-6">
            {{ $senderIds->links() }}
        </div>
    </div>
</div>
@endsection
