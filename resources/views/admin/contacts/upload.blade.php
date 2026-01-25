<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-100 leading-tight">Upload Contacts</h2>
            <a href="{{ route('admin.contacts.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Contacts
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                    <h3 class="text-sm font-medium text-red-800 dark:text-red-400 mb-2">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700 dark:text-red-400">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                <form action="{{ route('admin.contacts.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Form Header -->
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700">
                        <div class="flex items-center">
                            <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Bulk Import Contacts</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Upload CSV or Excel file with contact information</p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6 space-y-6">
                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Select File <span class="text-red-500">*</span>
                            </label>
                            <div x-data="{ fileName: '', fileSize: '' }">
                                <label class="relative cursor-pointer">
                                    <input type="file" 
                                           name="file" 
                                           accept=".csv,.txt,.xlsx,.xls" 
                                           class="hidden"
                                           required
                                           @change="fileName = $event.target.files[0]?.name || ''; fileSize = $event.target.files[0] ? ($event.target.files[0].size / 1024 / 1024).toFixed(2) + ' MB' : ''" />
                                    <div class="flex items-center justify-center w-full px-6 py-8 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg hover:border-green-500 dark:hover:border-green-500 transition">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-slate-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                                                <span class="font-medium text-green-600 dark:text-green-400">Click to upload</span>
                                                <span x-show="!fileName"> or drag and drop</span>
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400" x-show="!fileName">
                                                CSV, TXT, XLSX, or XLS (MAX. 10MB)
                                            </p>
                                            <div x-show="fileName" class="mt-2">
                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100" x-text="fileName"></p>
                                                <p class="text-xs text-slate-500 dark:text-slate-400" x-text="fileSize"></p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Your file should have columns: <strong>name</strong> (or <strong>full_name</strong>) and <strong>phone</strong>
                            </p>
                        </div>

                        <!-- Contact Group Selection -->
                        <div>
                            <label for="contact_group_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Assign to Group <span class="text-red-500">*</span>
                            </label>
                            <select id="contact_group_id" 
                                    name="contact_group_id" 
                                    required
                                    class="w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                <option value="">-- Select a Group --</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }} ({{ $group->contactsCount() }} contacts)</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                All uploaded contacts will be assigned to the selected group
                            </p>
                        </div>

                        <!-- Info Boxes -->
                        <div class="space-y-3">
                            <!-- File Format Info -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-400 mb-1">File Format Requirements</h4>
                                        <ul class="text-xs text-blue-800 dark:text-blue-300 space-y-1">
                                            <li>• First row must contain column headers</li>
                                            <li>• Required column: <strong>phone</strong></li>
                                            <li>• Optional columns: <strong>name</strong> or <strong>full_name</strong></li>
                                            <li>• Phone numbers will be automatically normalized</li>
                                            <li>• Duplicate phone numbers will be skipped</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Tracking Info -->
                            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-purple-900 dark:text-purple-400 mb-1">Upload Tracking & Auditing</h4>
                                        <p class="text-xs text-purple-800 dark:text-purple-300">
                                            This upload will be tracked in the system. All imported contacts will be linked to this upload record for auditing purposes. System metrics will be automatically updated.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900 border-t border-slate-200 dark:border-slate-700 rounded-b-lg flex items-center justify-between">
                        <a href="{{ route('admin.contacts.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            Upload Contacts
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
