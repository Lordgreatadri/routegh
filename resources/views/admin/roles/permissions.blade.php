<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Manage Permissions</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Assign permissions to role: <span class="font-semibold">{{ $role->name }}</span></p>
            </div>
            <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Roles
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Permissions for {{ $role->name }}</h3>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Select the permissions you want to assign to this role</p>
                        </div>
                        <div class="text-sm text-slate-600 dark:text-slate-400">
                            <span class="font-semibold" id="selectedCount">{{ count($rolePermissions) }}</span> of {{ $permissions->count() }} selected
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.roles.updatePermissions', $role) }}" method="POST">
                    @csrf
                    
                    <div class="p-6">
                        @if($permissions->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($permissions as $permission)
                                    <label class="flex items-start p-4 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer transition group">
                                        <input type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permission->id }}" 
                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                               class="permission-checkbox mt-1 rounded border-slate-300 dark:border-slate-600 text-indigo-600 focus:ring-indigo-500 dark:bg-slate-700"
                                               onchange="updateCount()">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-slate-900 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                                {{ $permission->name }}
                                            </span>
                                            @if($permission->guard_name)
                                                <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400">
                                                    {{ $permission->guard_name }}
                                                </span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center justify-between">
                                <div class="flex gap-2">
                                    <button type="button" 
                                            onclick="selectAll()" 
                                            class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium transition">
                                        Select All
                                    </button>
                                    <button type="button" 
                                            onclick="deselectAll()" 
                                            class="px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium transition">
                                        Deselect All
                                    </button>
                                </div>
                                <button type="submit" 
                                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                    <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Update Permissions
                                </button>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">No permissions available</p>
                                <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition">
                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create Permission
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Role Info Card -->
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                <h4 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4">Role Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Role Name</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $role->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Users with Role</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $role->users->count() }}</p>
                        </div>
                    </div>
                    <div class="flex items-center p-3 rounded-lg bg-slate-50 dark:bg-slate-700/50">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Guard Name</p>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $role->guard_name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateCount();
        }

        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateCount();
        }

        function updateCount() {
            const checked = document.querySelectorAll('.permission-checkbox:checked').length;
            document.getElementById('selectedCount').textContent = checked;
        }
    </script>
</x-app-layout>
