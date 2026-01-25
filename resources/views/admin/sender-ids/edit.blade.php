@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8 max-w-lg">
    <h2 class="text-2xl font-bold mb-4 text-white">Edit Sender ID</h2>
    <form action="{{ route('admin.sender-ids.update', $senderId) }}" method="POST" class="bg-slate-800 shadow-lg rounded-lg p-8">
        @csrf
        @method('PUT')
        <div class="mb-6">
            <label for="user_id" class="block text-slate-200 font-semibold mb-2">User <span class="text-red-400">*</span></label>
            <select name="user_id" id="user_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                <option value="">Select user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($user->id == $senderId->user_id) selected @endif>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-6">
            <label for="sender_id" class="block text-slate-200 font-semibold mb-2">Sender ID <span class="text-red-400">*</span></label>
            <input type="text" name="sender_id" id="sender_id" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full" required maxlength="11" value="{{ $senderId->sender_id }}">
        </div>
        <div class="mb-6">
            <label for="description" class="block text-slate-200 font-semibold mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">{{ $senderId->description }}</textarea>
        </div>
        <div class="mb-6 grid grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-slate-200 font-semibold mb-2">Status</label>
                <select name="status" id="status" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                    <option value="active" @if($senderId->status == 'active') selected @endif>Active</option>
                    <option value="inactive" @if($senderId->status == 'inactive') selected @endif>Inactive</option>
                </select>
            </div>
            <div>
                <label for="approval_status" class="block text-slate-200 font-semibold mb-2">Approval</label>
                <select name="approval_status" id="approval_status" class="bg-slate-900 border border-slate-700 text-slate-100 rounded px-4 py-2 w-full">
                    <option value="pending" @if($senderId->approval_status == 'pending') selected @endif>Pending</option>
                    <option value="approved" @if($senderId->approval_status == 'approved') selected @endif>Approved</option>
                    <option value="rejected" @if($senderId->approval_status == 'rejected') selected @endif>Rejected</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold transition">Update Sender ID</button>
    </form>
</div>
@endsection
