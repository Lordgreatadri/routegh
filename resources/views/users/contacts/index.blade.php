@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-4">
    <div class="bg-slate-800 text-slate-100 shadow rounded-lg p-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold">Contacts</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('contacts.create') }}" class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 rounded">Add contact</a>
                <form action="{{ route('contacts.upload') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                    @csrf
                    <label class="sr-only">Group</label>
                    <select name="group_id" required class="text-sm text-slate-200 bg-slate-700 border border-slate-600 rounded p-2">
                        <option value="" disabled selected>Select group</option>
                        @foreach($groups as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                    <input type="file" name="file" accept=".csv,.xlsx,.xls" class="text-sm text-slate-200" required />
                    <button class="px-3 py-2 bg-slate-600 hover:bg-slate-500 rounded" type="submit">Upload</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="text-left text-sm text-slate-300">
                        <th class="px-3 py-2">Name</th>
                        <th class="px-3 py-2">Phone</th>
                        <th class="px-3 py-2">Group</th>
                        <th class="px-3 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($contacts as $contact)
                        <tr class="text-sm">
                            <td class="px-3 py-2">{{ $contact->name }}</td>
                            <td class="px-3 py-2">{{ $contact->phone }}</td>
                            <td class="px-3 py-2">{{ optional($contact->contactGroup)->name }}</td>
                            <td class="px-3 py-2">
                                <a href="{{ route('contacts.edit', $contact) }}" class="text-indigo-400 mr-2">Edit</a>
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete contact?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-4 text-slate-400">No contacts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            @if($contacts->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="text-sm text-slate-400">
                        Showing <span class="font-medium">{{ $contacts->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $contacts->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $contacts->total() }}</span> contacts
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="per_page" class="text-sm text-slate-400">Per page:</label>
                            <select id="per_page" onchange="window.location.href = '{{ route('contacts.index') }}' + (window.location.search.includes('group_id') ? window.location.search + '&' : '?') + 'per_page=' + this.value" class="rounded border-slate-600 bg-slate-700 text-slate-200 text-sm px-2 py-1">
                                <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page', 15) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 15) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 15) == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        {{ $contacts->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
