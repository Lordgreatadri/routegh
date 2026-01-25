@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Edit Sender ID</h2>
    <form action="{{ route('users.sender-ids.update', $senderId) }}" method="POST" class="bg-white shadow rounded p-4 max-w-lg">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="sender_id" class="block text-gray-700">Sender ID <span class="text-red-500">*</span></label>
            <input type="text" name="sender_id" id="sender_id" class="form-input mt-1 block w-full" required maxlength="11" value="{{ $senderId->sender_id }}">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700">Description</label>
            <textarea name="description" id="description" class="form-input mt-1 block w-full">{{ $senderId->description }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>
</div>
@endsection
