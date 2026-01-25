@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <div class="bg-slate-800 text-slate-100 shadow rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Contact Groups</h2>
            <a href="{{ route('contact-groups.create') }}" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 rounded">New Group</a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="space-y-2">
            @forelse($groups as $group)
                <div class="p-3 bg-slate-700 rounded flex items-center justify-between">
                    <div>
                        <div class="font-medium">{{ $group->name }}</div>
                        <div class="text-sm text-slate-400">{{ $group->contactsCount() }} contacts</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('contact-groups.edit', $group) }}" class="text-indigo-300">Edit</a>
                        <form action="{{ route('contact-groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Delete group?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="text-slate-400">No groups yet.</div>
            @endforelse
        </div>

        <div class="mt-4">{{ $groups->links() }}</div>
    </div>
</div>
@endsection
