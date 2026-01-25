<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Message</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <h3 class="text-lg font-medium mb-2">To: {{ $message->recipient_number ?? '—' }}</h3>
                <div class="text-sm text-gray-500 dark:text-slate-300 mb-2">
                    Sender ID: {{ $message->smsSender ? $message->smsSender->sender_id : '—' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-slate-300 mb-4">Status: {{ ucfirst($message->status) }} • Sent: {{ $message->sent_at ? $message->sent_at->diffForHumans() : '—' }}</div>

                <div class="bg-gray-50 dark:bg-slate-900 p-4 rounded">
                    <pre class="whitespace-pre-wrap">{{ $message->message_content }}</pre>
                </div>

                @if($message->error_message)
                <div class="mt-4 text-red-500">Error: {{ $message->error_message }}</div>
                @endif

                <div class="mt-4 text-right">
                    <a href="{{ route('messages.index') }}" class="px-3 py-2 bg-gray-200 rounded dark:bg-slate-700">Back</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
