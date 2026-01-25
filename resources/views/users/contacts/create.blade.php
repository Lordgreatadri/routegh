@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-4">
    <div class="bg-slate-800 text-slate-100 shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Add Contact</h2>

        <form action="{{ route('contacts.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm text-slate-200">Name</label>
                    <input name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded p-2 border border-slate-600 bg-slate-700 text-slate-100" />
                    @error('name') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-slate-200">Phone</label>
                    <input name="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded p-2 border border-slate-600 bg-slate-700 text-slate-100" />
                    @error('phone') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm text-slate-200">Group</label>
                <select name="contact_group_id" class="mt-1 block w-full rounded p-2 border border-slate-600 bg-slate-700 text-slate-100">
                    <option value="">-- None --</option>
                    @foreach($groups as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mt-6 flex gap-2">
                <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded">Save</button>
                <a href="{{ route('contacts.index') }}" class="px-4 py-2 bg-slate-600 rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
