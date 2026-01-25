<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">SMS Message</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <h3 class="text-lg font-medium mb-2">{{ $smsMessage->phone ?? '—' }}</h3>
                <div class="text-sm text-gray-500 dark:text-slate-300 mb-4">Status: {{ ucfirst($smsMessage->status) }} • Sent: {{ $smsMessage->sent_at ? $smsMessage->sent_at->diffForHumans() : '—' }}</div>

                <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                    <pre class="whitespace-pre-wrap">{{ $smsMessage->message }}</pre>
                </div>

                @if($smsMessage->provider_status)
                <div class="mt-4 text-sm text-gray-600 dark:text-slate-300">Provider status: {{ $smsMessage->provider_status }}</div>
                @endif

                <div class="mt-4 text-right">
                    <a href="{{ route('sms-messages.index') }}" class="px-3 py-2 bg-gray-200 rounded dark:bg-slate-700">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
