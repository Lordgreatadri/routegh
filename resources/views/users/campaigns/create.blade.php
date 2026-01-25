<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">New Campaign</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <form method="POST" action="{{ route('campaigns.store') }}" id="create-campaign-form">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Sender ID <span class="text-red-400">*</span></label>
                        <select name="sms_sender_id" required class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" required>
                            <option value="">-- Select Sender ID --</option>
                            
                            @foreach($senderIds as $id => $sender)
                                <option value="{{ $id }}" {{ old('sms_sender_id') == $id ? 'selected' : '' }}>{{ $sender }}</option>
                            @endforeach
                        </select>
                        @error('sms_sender_id')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                        @error('title')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Message</label>
                        <textarea name="message" rows="4" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">{{ old('message') }}</textarea>
                        @error('message')<div class="text-red-500 text-sm mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Contact Group (recommended)</label>
                        <select name="contact_group_id[]" multiple class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            @foreach($contactGroups as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">You may select multiple groups. Selecting groups will use those groups' contacts server-side (avoids loading large lists).</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">Selecting a group will use that group's contacts server-side (avoids loading large lists).</div>
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="use-manual" class="form-checkbox" />
                            <span class="ml-2 text-sm text-gray-700 dark:text-slate-300">Use manual contact IDs instead</span>
                        </label>
                        <div id="manual-contacts-wrap" class="mt-2 hidden">
                            <label class="block text-sm text-gray-500 dark:text-slate-300">Paste contact IDs (comma-separated)</label>
                            <textarea id="manual-contacts" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" rows="3" placeholder="e.g. 12,34,56"></textarea>
                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">Provide contact IDs separated by commas. On submit these will be converted to contact inputs.</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Schedule (optional)</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}" class="mt-1 block w-full rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" />
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('campaigns.index') }}" class="px-3 py-2 bg-gray-200 rounded dark:bg-slate-700">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create Campaign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const useManual = document.getElementById('use-manual');
    const manualWrap = document.getElementById('manual-contacts-wrap');
    const form = document.getElementById('create-campaign-form');

    useManual.addEventListener('change', function () {
        if (this.checked) {
            manualWrap.classList.remove('hidden');
        } else {
            manualWrap.classList.add('hidden');
        }
    });

    form.addEventListener('submit', function (e) {
        // Remove any previously added hidden contact inputs
        document.querySelectorAll('input[name="contacts[]"]').forEach(n => n.remove());

        if (useManual.checked) {
            const raw = document.getElementById('manual-contacts').value || '';
            const ids = raw.split(',').map(s => s.trim()).filter(Boolean);
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'contacts[]';
                input.value = id;
                form.appendChild(input);
            });
        }
    });
});
</script>
</x-app-layout>
