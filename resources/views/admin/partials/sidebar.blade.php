<aside class="hidden lg:block fixed left-8 top-20 w-64 h-[calc(100vh-5rem)]">
    <div class="bg-slate-800 text-slate-100 rounded p-4 shadow-lg overflow-auto h-full">
        <h4 class="font-semibold mb-3">Admin</h4>
        <nav class="flex flex-col gap-2 text-sm">
            <!-- Main Section -->
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mt-2 mb-1 px-3">Main Section</div>
            
            <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700' : '' }}">Dashboard</a>
            
            <!-- Pending Users with Badge -->
            @php
                $pendingCount = \App\Models\User::where('status', 'pending')->count();
            @endphp
            <a href="{{ route('admin.users.pending') }}" 
               class="px-3 py-2 rounded hover:bg-slate-700 flex items-center justify-between {{ request()->routeIs('admin.users.pending') ? 'bg-slate-700' : '' }}">
                <span>Pending Users</span>
                @if($pendingCount > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                        {{ $pendingCount }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.users.index') ? 'bg-slate-700' : '' }}">All Users</a>
            <a href="{{ route('admin.contacts.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.contacts.*') ? 'bg-slate-700' : '' }}">Contacts</a>
            <a href="{{ route('admin.contact-groups.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.contact-groups.*') ? 'bg-slate-700' : '' }}">Contact Groups</a>
            <a href="{{ route('admin.campaigns.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.campaigns.*') ? 'bg-slate-700' : '' }}">Campaigns</a>
            <a href="{{ route('admin.messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.messages.*') ? 'bg-slate-700' : '' }}">SMS Messages</a>
            <a href="{{ route('admin.uploads.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.uploads.*') ? 'bg-slate-700' : '' }}">Uploads</a>
            @php
                $pendingSenderIds = \App\Models\SmsSenderId::where('approval_status', 'pending')->count();
            @endphp
            <a href="{{ route('admin.sender-ids.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 flex items-center justify-between {{ request()->routeIs('admin.sender-ids.*') ? 'bg-slate-700' : '' }}">
                <span>Sender IDs</span>
                @if($pendingSenderIds > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-yellow-500 rounded-full">
                        {{ $pendingSenderIds }}
                    </span>
                @endif
            </a>
            <a href="{{ route('admin.api-logs.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.api-logs.*') ? 'bg-slate-700' : '' }}">API Logs</a>
            
            <!-- Separator -->
            <div class="my-2 border-t border-slate-600"></div>
            
            <!-- Account & System Section -->
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 px-3">Account & System</div>
            
            <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('profile.edit') ? 'bg-slate-700' : '' }}">Settings</a>
            <a href="{{ route('admin.roles.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.roles.*') ? 'bg-slate-700' : '' }}">Roles</a>
            <a href="{{ route('admin.permissions.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.permissions.*') ? 'bg-slate-700' : '' }}">Permissions</a>
            <a href="{{ route('admin.admin-messages.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.admin-messages.*') ? 'bg-slate-700' : '' }}">Support Messages</a>
            
            <!-- Contact Messages with Badge -->
            @php
                $newContactMessages = \App\Models\ContactMessage::where('status', 'new')->count();
            @endphp
            <a href="{{ route('admin.contact-messages.index') }}" 
               class="px-3 py-2 rounded hover:bg-slate-700 flex items-center justify-between {{ request()->routeIs('admin.contact-messages.*') ? 'bg-slate-700' : '' }}">
                <span>Contact Messages</span>
                @if($newContactMessages > 0)
                    <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-blue-600 rounded-full">
                        {{ $newContactMessages }}
                    </span>
                @endif
            </a>
            
            <a href="{{ route('admin.logs.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('admin.logs.*') ? 'bg-slate-700' : '' }}">System Logs</a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-slate-700 text-red-400 hover:text-red-300">
                    Logout
                </button>
            </form>
        </nav>
    </div>
</aside>
