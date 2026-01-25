@extends('layouts.app')

@section('content')
<script>
    window.profileFormData = {
        name: {!! json_encode(old('name', $user->name ?? '')) !!},
        email: {!! json_encode(old('email', $user->email ?? '')) !!},
        phone: {!! json_encode(old('phone', $user->phone ?? '')) !!}
    };
</script>

<div class="max-w-4xl mx-auto p-6" x-data="profileForm()">
    <!-- Success Messages -->
    @if(session('status'))
        <div x-data="{ show: true }" 
             x-init="setTimeout(() => show = false, 4000)" 
             x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="mb-6 rounded-lg bg-green-500/10 border border-green-500/20 p-4 text-green-400">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Account Settings</h1>
        <p class="text-slate-400 mt-2">Manage your profile information and account preferences</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-slate-800 rounded-lg shadow-lg overflow-hidden">
        <div class="border-b border-slate-700">
            <nav class="flex space-x-4 px-6" aria-label="Tabs">
                <button @click="activeTab = 'profile'" 
                        :class="activeTab === 'profile' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Profile Information
                </button>
                <button @click="activeTab = 'password'" 
                        :class="activeTab === 'password' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Change Password
                </button>
                <button @click="activeTab = 'danger'" 
                        :class="activeTab === 'danger' ? 'border-red-500 text-red-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-300'"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                    Danger Zone
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Profile Information Tab -->
            <div x-show="activeTab === 'profile'" x-transition>
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-white">Profile Information</h2>
                    <p class="text-sm text-slate-400 mt-1">Update your account profile information</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Name and Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-slate-200 mb-2">Full Name <span class="text-red-400">*</span></label>
                                <input x-model="formData.name" 
                                       name="name" 
                                       id="name"
                                       type="text"
                                       value="{{ old('name', $user->name) }}"
                                       :class="errors.name ? 'border-red-500 focus:ring-red-500' : 'border-slate-600 focus:ring-indigo-500'"
                                       class="w-full rounded-lg p-3 border bg-slate-700 text-slate-100 focus:ring-2 focus:border-transparent transition"
                                       placeholder="Enter your full name" />
                                <p x-show="errors.name" x-text="errors.name" class="text-red-400 text-sm mt-1"></p>
                                @error('name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-200 mb-2">Email Address</label>
                                <input x-model="formData.email" 
                                       name="email" 
                                       id="email"
                                       type="email"
                                       value="{{ old('email', $user->email) }}"
                                       :class="errors.email ? 'border-red-500 focus:ring-red-500' : 'border-slate-600 focus:ring-indigo-500'"
                                       class="w-full rounded-lg p-3 border bg-slate-700 text-slate-100 focus:ring-2 focus:border-transparent transition"
                                       placeholder="your@email.com" />
                                <p x-show="errors.email" x-text="errors.email" class="text-red-400 text-sm mt-1"></p>
                                @error('email') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-200 mb-2">Phone Number <span class="text-red-400">*</span></label>
                            <input x-model="formData.phone" 
                                   name="phone" 
                                   id="phone"
                                   type="text"
                                   value="{{ old('phone', $user->phone) }}"
                                   :class="errors.phone ? 'border-red-500 focus:ring-red-500' : 'border-slate-600 focus:ring-indigo-500'"
                                   class="w-full rounded-lg p-3 border bg-slate-700 text-slate-100 focus:ring-2 focus:border-transparent transition"
                                   placeholder="020XXXXXXXXX" />
                            
                            <div class="flex items-center justify-between mt-2">
                                <p x-show="errors.phone" x-text="errors.phone" class="text-red-400 text-sm"></p>
                                <div>
                                    @if(!$user->phone_verified_at)
                                        <a href="{{ route('phone.verify') }}" class="text-sm text-yellow-400 hover:text-yellow-300 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Not verified - Verify now
                                        </a>
                                    @else
                                        <span class="text-sm text-green-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Verified {{ $user->phone_verified_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @error('phone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Company Name -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-slate-200 mb-2">Company Name<span class="text-red-400">*</span></label>
                            <input name="company_name" 
                                   id="company_name"
                                   type="text"
                                   value="{{ old('company_name', $user->company_name) }}"
                                   class="w-full rounded-lg p-3 border border-slate-600 bg-slate-700 text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                   placeholder="Your company or organization name" />
                            @error('company_name') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Account Info (Read-only) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700">
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Account Status</label>
                                <div class="flex items-center">
                                    @if($user->status === 'approved')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-slate-500/10 text-slate-400 border border-slate-500/20">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Member Since</label>
                                <p class="text-slate-200">{{ $user->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex items-center justify-between pt-6 border-t border-slate-700">
                        <a href="{{ route('users.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 text-slate-300 rounded-lg hover:bg-slate-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Dashboard
                        </a>
                        <button type="submit" 
                                :disabled="submitting"
                                @click="handleSubmit($event)"
                                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium disabled:opacity-50 disabled:cursor-not-allowed transition">
                            <svg x-show="submitting" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div x-show="activeTab === 'password'" x-transition style="display: none;">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-200">Password Change</h3>
                    <p class="mt-1 text-sm text-slate-400">Use "Forgot Password" to reset your password</p>
                    <div class="mt-6">
                        <a href="{{ route('password.request') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            Reset Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Danger Zone Tab -->
            <div x-show="activeTab === 'danger'" x-transition style="display: none;">
                <div class="mb-6">
                    <h2 class="text-xl font-semibold text-red-400">Danger Zone</h2>
                    <p class="text-sm text-slate-400 mt-1">Irreversible actions that affect your account</p>
                </div>

                <div class="rounded-lg bg-red-500/10 border border-red-500/20 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-red-400">Delete Account</h3>
                            <p class="mt-2 text-sm text-slate-300">
                                Once you delete your account, there is no going back. This will permanently delete your account, 
                                all your campaigns, messages, contacts, and uploaded files.
                            </p>
                            
                            <div class="mt-6" x-data="{ showDeleteModal: false, deletePassword: '' }">
                                <button @click="showDeleteModal = true" 
                                        type="button"
                                        class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete My Account
                                </button>

                                <!-- Delete Confirmation Modal -->
                                <div x-show="showDeleteModal" 
                                     x-cloak
                                     class="fixed inset-0 z-50 overflow-y-auto" 
                                     style="display: none;">
                                    <div class="flex items-center justify-center min-h-screen px-4">
                                        <!-- Background overlay -->
                                        <div x-show="showDeleteModal"
                                             x-transition:enter="ease-out duration-300"
                                             x-transition:enter-start="opacity-0"
                                             x-transition:enter-end="opacity-100"
                                             @click="showDeleteModal = false"
                                             class="fixed inset-0 bg-black bg-opacity-75 transition-opacity">
                                        </div>

                                        <!-- Modal panel -->
                                        <div x-show="showDeleteModal"
                                             x-transition
                                             class="relative bg-slate-800 rounded-lg px-4 pt-5 pb-4 shadow-xl sm:max-w-lg sm:w-full sm:p-6">
                                            
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-500/10 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left flex-1">
                                                    <h3 class="text-lg leading-6 font-medium text-white">
                                                        Delete Account
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-slate-300">
                                                            Are you absolutely sure? This action cannot be undone.
                                                        </p>
                                                    </div>

                                                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
                                                        @csrf
                                                        @method('DELETE')

                                                        <div>
                                                            <label class="block text-sm font-medium text-slate-200 mb-2">
                                                                Enter your password to confirm
                                                            </label>
                                                            <input x-model="deletePassword"
                                                                   type="password" 
                                                                   name="password" 
                                                                   required
                                                                   class="w-full rounded-lg p-3 border border-slate-600 bg-slate-700 text-slate-100 focus:ring-2 focus:ring-red-500"
                                                                   placeholder="Your password" />
                                                        </div>

                                                        <div class="mt-5 sm:flex sm:flex-row-reverse gap-3">
                                                            <button type="submit"
                                                                    class="w-full inline-flex justify-center rounded-lg px-4 py-2 bg-red-600 text-white hover:bg-red-700 sm:w-auto transition">
                                                                Yes, Delete My Account
                                                            </button>
                                                            <button type="button"
                                                                    @click="showDeleteModal = false"
                                                                    class="mt-3 w-full inline-flex justify-center rounded-lg px-4 py-2 bg-slate-700 text-slate-200 hover:bg-slate-600 sm:mt-0 sm:w-auto transition">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
