@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6 ">
        @if(session('status'))
            <div x-data="{ show: true }" x-init="setTimeout(()=> show = false, 4000)" x-show="show" x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="mb-4 rounded-md bg-blue-50 p-3 text-blue-800">
                {{ session('status') }}
            </div>
        @endif

        {{-- reusable toast component (reads session('accountDeleted') ) --}}
        @include('components.toast')
    <div class="bg-slate-800 shadow rounded-lg p-6 text-slate-100">
        <h2 class="text-2xl font-semibold mb-4 text-white">Edit Profile</h2>

        @if(session('status') === 'profile-updated')
            <div class="mb-4 rounded-md bg-green-50 p-3 text-green-800">Profile updated.</div>
        @endif

        @if(session('status') && str_contains(session('status'), 'Phone changed'))
            <div class="mb-4 rounded-md bg-yellow-50 p-3 text-yellow-800">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" x-data="profileForm()" @submit.prevent="submitForm($event)">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-200">Name</label>
                    <input x-model="name" name="name" :class="nameError ? 'border-red-500' : 'border-slate-600'" class="mt-1 block w-full rounded p-2 border bg-slate-700 text-slate-100" @blur="validateName" />
                    <p x-show="nameError" x-text="nameError" class="text-red-600 text-sm mt-1"></p>
                    @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-200">Email</label>
                    <input x-model="email" name="email" :class="emailError ? 'border-red-500' : 'border-slate-600'" class="mt-1 block w-full rounded p-2 border bg-slate-700 text-slate-100" @blur="validateEmail" />
                    <p x-show="emailError" x-text="emailError" class="text-red-400 text-sm mt-1"></p>
                    @error('email') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-200">Phone</label>
                <input x-model="phone" name="phone" :class="phoneError ? 'border-red-500' : 'border-slate-600'" class="mt-1 block w-full rounded p-2 border bg-slate-700 text-slate-100" @blur="validatePhone" />
                <div class="flex items-center justify-between mt-1">
                    <p x-show="phoneError" x-text="phoneError" class="text-red-600 text-sm"></p>
                    <div>
                        @if(!$user->phone_verified_at)
                            <a href="{{ route('phone.verify') }}" class="text-sm text-yellow-700 underline">Phone not verified &middot; Verify now</a>
                        @else
                            <span class="text-sm text-green-700">Verified {{ $user->phone_verified_at->diffForHumans() }}</span>
                        @endif
                    </div>
                </div>
                @error('phone') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-slate-200">Company name</label>
                <input name="company_name" value="{{ old('company_name', $user->company_name) }}" class="mt-1 block w-full rounded p-2 border border-slate-600 bg-slate-700 text-slate-100" />
                @error('company_name') <p class="text-red-400 text-sm">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button type="submit" :disabled="!isValid || submitting" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-medium disabled:opacity-60 min-w-[96px]">
                    <span class="inline-flex items-center">
                        <span class="w-5 flex-none mr-2">
                            <svg x-show="submitting" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                        </span>
                        <span x-show="!submitting">Save</span>
                        <span x-show="submitting">Saving...</span>
                    </span>
                </button>
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-slate-600 text-white rounded hover:bg-slate-500">Cancel</a>
            </div>

            <div class="mt-8 border-t pt-6">
                <h3 class="text-lg font-semibold text-red-600">Danger Zone</h3>
                <p class="text-sm text-gray-600 mt-1">Permanently delete your account and all associated data. This action cannot be undone.</p>

                <div class="mt-4">
                    <div x-data="{ showDelete: (window.__showDelete ?? false), password: (window.__password ?? ''), error: (window.__deleteError ?? '') }" @account-deleted.window="showDelete = false">
                        <button @click="showDelete = true" type="button" class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded">
                            Delete Account
                        </button>

                        <template x-if="showDelete">
                            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                    <h4 class="text-lg font-semibold text-gray-800">Confirm account deletion</h4>
                                    <p class="text-sm text-gray-600 mt-2">Enter your password to confirm. This cannot be undone.</p>

                                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4" @submit.prevent="$el.querySelector('button[type=submit]').disabled = true; $el.querySelector('button[type=submit]').classList.add('opacity-60'); $el.submit();">
                                        @csrf
                                        @method('DELETE')

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Password</label>
                                            <input x-model="password" name="password" type="password" required class="mt-1 block w-full rounded p-2 border border-gray-300" />
                                            @error('password')
                                                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <p x-text="error" x-show="error" class="text-red-600 text-sm mt-2"></p>

                                        <div class="mt-4 flex justify-end gap-3">
                                            <button type="button" @click="showDelete = false" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded">Yes, delete my account</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </form>

        {{-- profile-form.js is now bundled in `resources/js/app.js` and loaded site-wide via @vite in layout --}}
    </div>
</div>

<script>
    function profileForm() {
        return {
            name: {!! json_encode(old('name', $user->name ?? '')) !!},
            email: {!! json_encode(old('email', $user->email ?? '')) !!},
            phone: {!! json_encode(old('phone', $user->phone ?? '')) !!},
            nameError: '',
            emailError: '',
            phoneError: '',
            submitting: false,
            get isValid() {
                return !this.nameError && !this.emailError && !this.phoneError && this.name.trim().length > 0 && this.phone.trim().length > 0;
            },
            validateName() {
                if (!this.name || this.name.trim().length < 2) {
                    this.nameError = 'Please enter your name (at least 2 characters)';
                } else {
                    this.nameError = '';
                }
            },
            validateEmail() {
                if (!this.email) { this.emailError = ''; return; }
                const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(".+"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
                this.emailError = re.test(this.email) ? '' : 'Please enter a valid email address';
            },
            validatePhone() {
                const digits = this.phone.replace(/\D/g, '');
                if (!digits || digits.length < 7 || digits.length > 15) {
                    this.phoneError = 'Please enter a valid phone number (7-15 digits)';
                } else {
                    this.phoneError = '';
                }
            },
            submitForm(event) {
                console.log('Submit clicked');
                this.validateName(); 
                this.validateEmail(); 
                this.validatePhone();
                
                console.log('Validation results:', {
                    nameError: this.nameError,
                    emailError: this.emailError, 
                    phoneError: this.phoneError,
                    isValid: this.isValid
                });
                
                if (!this.isValid) {
                    console.log('Form invalid, preventing submission');
                    event.preventDefault();
                    return;
                }
                
                console.log('Form valid, submitting...');
                this.submitting = true;
                // Let the form submit naturally
                event.target.submit();
            }
        }
    }
</script>

@endsection

@if($errors->userDeletion->isNotEmpty())
    <script>
        window.__showDelete = true;
        window.__deleteError = {!! json_encode($errors->userDeletion->first('password')) !!};
    </script>
@endif
 