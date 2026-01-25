@props(['title' => 'Confirm', 'description' => 'Are you sure?', 'confirmText' => 'Yes, continue'])

<div x-data="{ open: false, action: null, method: 'POST', name: null, description: '{{ $description }}' }" @open-confirm.window="open = true; action = $event.detail.action; method = $event.detail.method || 'POST'; name = $event.detail.name || null; description = $event.detail.description || '{{ $description }}'" x-cloak>
    <template x-if="open">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-lg w-full max-w-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2" x-text="name ? description + ' &quot;' + name + '&quot;' : description"></p>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="open = false" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 rounded-lg font-medium transition">Cancel</button>
                    <form :action="action" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="_method" :value="method">
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">{{ $confirmText }}</button>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
