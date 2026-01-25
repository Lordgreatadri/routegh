<aside class="hidden lg:block fixed left-8 top-20 w-64 h-[calc(100vh-5rem)]">
    <div class="bg-slate-800 text-slate-100 rounded p-4 shadow-lg overflow-auto h-full">
        <h4 class="font-semibold mb-3">Menu</h4>
        <nav class="flex flex-col gap-2 text-sm">
            <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700' : '' }}">Dashboard</a>
            <a href="{{ route('uploads.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('uploads.*') ? 'bg-slate-700' : '' }}">Uploads</a>
            <a href="{{ route('contacts.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('contacts.*') ? 'bg-slate-700' : '' }}">Contacts</a>
            <a href="{{ route('campaigns.index') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('campaigns.*') ? 'bg-slate-700' : '' }}">Campaigns</a>
            <a href="{{ route('profile.edit') }}" class="px-3 py-2 rounded hover:bg-slate-700 {{ request()->routeIs('profile.edit') ? 'bg-slate-700' : '' }}">Settings</a>
        </nav>
    </div>
</aside>
