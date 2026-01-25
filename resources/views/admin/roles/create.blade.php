<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Create Role</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 dark:text-slate-100 p-6 rounded shadow-sm">
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">Role name</label>
                        <input id="name" name="name" type="text" required class="mt-1 block w-full border rounded px-3 py-2 bg-white dark:bg-slate-900 text-gray-900 dark:text-slate-100" />
                        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Create</button>
                        <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-red-200 text-gray-900 rounded">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
