@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-6 text-white">Add Sender ID</h2>
    <form action="{{ route('users.sender-ids.store') }}" method="POST" class="bg-slate-800 shadow-lg rounded-lg p-8 max-w-lg mx-auto">
        @csrf
        <div class="mb-6">
            <label for="sender_id" class="block text-slate-200 font-semibold mb-2">Sender ID <span class="text-red-400">*</span></label>
            <input type="text" name="sender_id" id="sender_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required maxlength="11" placeholder="e.g. MYBRAND">
        </div>
        <div class="mb-6">
            <label for="description" class="block text-slate-200 font-semibold mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Describe the purpose of this Sender ID "></textarea>
        </div>
        <div class="mb-6">
            <span class="block text-yellow-400 bg-yellow-900/40 rounded px-3 py-2 text-sm">
                Your Sender ID request will be reviewed by an admin. You will be notified once approved or rejected.<br>
                <span class="block mt-2 text-yellow-200">
                    <strong>Requirements:</strong><br>
                    • Must have a maximum of 11 characters<br>
                    • Must <span class="font-bold">NOT</span> include any special characters (e.g. <span class="font-mono">!&quot;#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~</span>)
                </span>
            </span>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Submit for Review</button>
    </form>
</div>
@endsection
