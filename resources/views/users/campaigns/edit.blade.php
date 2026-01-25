<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Edit Campaign</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <form method="POST" action="{{ route('campaigns.update', $campaign) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Title</label>
                        <input type="text" name="title" value="{{ old('title', $campaign->title) }}" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                        @error('title')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Message</label>
                        <textarea name="message" rows="4" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ old('message', $campaign->message) }}</textarea>
                        @error('message')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Schedule (optional)</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('campaigns.show', $campaign) }}" class="px-3 py-2 bg-gray-200 rounded dark:bg-slate-700">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
