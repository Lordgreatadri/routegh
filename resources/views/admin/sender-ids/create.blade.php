@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-4 text-white">Add Sender ID</h2>
    <form action="{{ route('admin.sender-ids.store') }}" method="POST" class="bg-slate-800 shadow-lg rounded-lg p-8">
        @csrf
        <div class="mb-6">
            <span class="block text-yellow-400 bg-yellow-900/40 rounded px-3 py-2 text-sm mb-4">
                <strong>Notice:</strong> Created and approved Sender ID with <strong>active</strong> status will be used to send any outbound SMS message.<br>
                <span class="block mt-2 text-yellow-200">
                    <strong>Requirements:</strong><br>
                    • Must have a maximum of 11 characters<br>
                    • Must <span class="font-bold">NOT</span> include any special characters (e.g. <span class="font-mono">!&quot;#$%&amp;'()*+,-./:;&lt;=&gt;?@[\]^_`{|}~</span>)
                </span>
            </span>
            <label for="user_id" class="block text-slate-200 font-semibold mb-2">User <span class="text-red-400">*</span></label>
            <select name="user_id" id="user_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                <option value="">Select user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label for="sender_id" class="block text-slate-200 font-semibold mb-2">Sender ID <span class="text-red-400">*</span></label>
            <input type="text" name="sender_id" id="sender_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full" required maxlength="11" placeholder="e.g. MYBRAND">
        </div>
        <div class="mb-6">
            <label for="description" class="block text-slate-200 font-semibold mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full" placeholder="Describe the purpose of this Sender ID "></textarea>
        </div>
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-slate-200 font-semibold mb-2">Status</label>
                <select name="status" id="status" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div>
                <label for="approval_status" class="block text-slate-200 font-semibold mb-2">Approval</label>
                <select name="approval_status" id="approval_status" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Create Sender ID</button>
    </form>
</div>
@endsection
