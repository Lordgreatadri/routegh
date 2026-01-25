@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Dev: Recent OTPs</h1>

    <div class="mb-4">
        <form method="GET" action="{{ route('dev.userotps') }}">
            <label class="block text-sm">Source</label>
            <select name="source" class="mt-1 border rounded p-2">
                <option value="db" {{ request('source','db') === 'db' ? 'selected' : '' }}>Database (hashed)</option>
                <option value="log" {{ request('source') === 'log' ? 'selected' : '' }}>Laravel log (plaintext, when using log driver)</option>
            </select>
            <label class="block text-sm mt-2">Limit</label>
            <input type="number" name="limit" value="{{ request('limit', 50) }}" class="mt-1 border rounded p-2 w-24" />
            <div class="mt-2">
                <button class="bg-blue-600 text-white px-3 py-1 rounded">Refresh</button>
            </div>
        </form>
    </div>

    @if($source === 'log')
        <h2 class="font-medium mb-2">Matched OTPs from log (last {{ $limit }} lines)</h2>
        @if(empty($matches))
            <div class="text-sm text-gray-600">No OTPs found in recent log lines.</div>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="text-left"><th class="p-2">Phone</th><th class="p-2">OTP</th><th class="p-2">Sample Log</th></tr>
                </thead>
                <tbody>
                @foreach($matches as $m)
                    <tr class="border-t"><td class="p-2">{{ $m['phone'] }}</td><td class="p-2">{{ $m['otp'] }}</td><td class="p-2 font-mono text-xs">{{ $m['line'] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @else
        <h2 class="font-medium mb-2">Recent DB `user_otps` rows (last {{ $limit }})</h2>
        @if($rows->isEmpty())
            <div class="text-sm text-gray-600">No user_otps rows found.</div>
        @else
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="text-left"><th class="p-2">Phone</th><th class="p-2">Created At</th><th class="p-2">Expired At</th></tr>
                </thead>
                <tbody>
                @foreach($rows as $r)
                    <tr class="border-t"><td class="p-2">{{ $r->phone_number }}</td><td class="p-2">{{ $r->created_at }}</td><td class="p-2">{{ $r->expired_at }}</td></tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endif

</div>
@endsection
