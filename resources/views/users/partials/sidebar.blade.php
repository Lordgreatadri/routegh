<aside x-data="{ open: false }" @toggle-user-sidebar.window="open = !open" class="fixed left-8 top-20 w-64 h-[calc(100vh-5rem)] lg:block">
    <div class="hidden lg:block">
        <div class="bg-slate-800 text-slate-100 rounded p-4 shadow-lg overflow-auto h-full">
            <h4 class="font-semibold mb-3">User</h4>
            <nav class="flex flex-col gap-2 text-sm">
                <a href="{{ route('users.dashboard') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('users.dashboard') ? 'bg-slate-700' : '' }}">Dashboard</a>
                <a href="{{ route('contacts.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('contacts.*') ? 'bg-slate-700' : '' }}">Contacts</a>
                <a href="{{ route('contact-groups.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('contact-groups.*') ? 'bg-slate-700' : '' }}">Groups</a>
                    <a href="{{ route('uploads.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('uploads.*') ? 'bg-slate-700' : '' }}">Uploads</a>
                <a href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('campaigns.*') ? 'bg-slate-700' : '' }}">Campaigns</a>
                <a href="{{ route('messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('messages.*') ? 'bg-slate-700' : '' }}">Messages</a>
                <a href="{{ route('users.sender-ids.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('users.sender-ids.*') ? 'bg-slate-700' : '' }}">Sender IDs</a>
                <a href="{{ route('sms-messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('sms-messages.*') ? 'bg-slate-700' : '' }}">SMS Messages</a>
                <a href="{{ route('user-messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('user-messages.*') ? 'bg-slate-700' : '' }}">Support</a>
                <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('profile.edit') ? 'bg-slate-700' : '' }}">Settings</a>
            </nav>
        </div>
    </div>

    <!-- Mobile slide-over -->
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-x-0" x-transition:leave-end="opacity-0 transform -translate-x-4" class="lg:hidden fixed inset-y-0 left-0 z-40 w-64 p-4">
        <div class="bg-slate-800 text-slate-100 rounded p-4 shadow-lg h-full overflow-auto">
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-semibold">Menu</h4>
                <button @click="open = false" class="p-2 rounded text-slate-200 hover:bg-slate-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <nav class="flex flex-col gap-2 text-sm">
                <a @click="open = false" href="{{ route('users.dashboard') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('users.dashboard') ? 'bg-slate-700' : '' }}">Dashboard</a>
                <a @click="open = false" href="{{ route('contacts.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('contacts.*') ? 'bg-slate-700' : '' }}">Contacts</a>
                <a @click="open = false" href="{{ route('contact-groups.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('contact-groups.*') ? 'bg-slate-700' : '' }}">Groups</a>
                    <a @click="open = false" href="{{ route('uploads.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('uploads.*') ? 'bg-slate-700' : '' }}">Uploads</a>
                <a @click="open = false" href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('campaigns.*') ? 'bg-slate-700' : '' }}">Campaigns</a>
                <a @click="open = false" href="{{ route('messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('messages.*') ? 'bg-slate-700' : '' }}">Messages</a>
                <a @click="open = false" href="{{ route('users.sender-ids.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('users.sender-ids.*') ? 'bg-slate-700' : '' }}">Sender IDs</a>
                <a @click="open = false" href="{{ route('sms-messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('sms-messages.*') ? 'bg-slate-700' : '' }}">SMS Messages</a>
                <a @click="open = false" href="{{ route('user-messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('user-messages.*') ? 'bg-slate-700' : '' }}">Support</a>
                <a @click="open = false" href="{{ route('profile.edit') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('profile.edit') ? 'bg-slate-700' : '' }}">Settings</a>
            </nav>
        </div>
    </div>
</aside>
