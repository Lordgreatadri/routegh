<x-app-layout>
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-800 text-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <nav class="flex items-center justify-between flex-wrap gap-3">
      <a href="{{ url('/') }}" class="flex items-center space-x-2 sm:space-x-3">
        <span class="inline-flex w-8 h-8 sm:w-10 sm:h-10 items-center justify-center rounded-lg bg-indigo-500 font-bold text-white text-sm sm:text-base">RG</span>
        <span class="text-base sm:text-lg font-semibold tracking-tight">RouteGH</span>
      </a>

      <div class="flex items-center flex-wrap gap-2 sm:gap-3">
        <!-- Animated Contact Us Button -->
        <a href="{{ route('contact.index') }}" 
           class="relative inline-flex items-center px-3 py-1.5 sm:px-5 sm:py-2.5 bg-gradient-to-r from-orange-500 to-pink-600 hover:from-orange-600 hover:to-pink-700 rounded-lg text-white text-xs sm:text-sm font-medium shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 animate-pulse">
          <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
          <span class="hidden sm:inline">Contact Us</span>
          <span class="sm:hidden">Contact</span>
          <span class="absolute top-0 right-0 -mt-1 -mr-1 flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
          </span>
        </a>
        
        @auth
          <a href="{{ route('users.dashboard') }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-indigo-500 hover:bg-indigo-600 rounded-md text-white text-xs sm:text-sm font-medium">Dashboard</a>
        @else
          <a href="{{ route('login') }}" class="text-slate-200 hover:text-white text-sm sm:text-base">Log in</a>
          <a href="{{ route('register') }}" class="inline-flex items-center px-3 py-1.5 sm:px-4 sm:py-2 bg-indigo-500 hover:bg-indigo-600 rounded-md text-white text-xs sm:text-sm font-medium whitespace-nowrap">Get started</a>
        @endauth
      </div>
    </nav>

    <header class="mt-12 grid gap-8 lg:grid-cols-2 items-center">
      <div>
        <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Send SMS campaigns with clarity and confidence</h1>
        <p class="mt-4 text-slate-300 max-w-xl">Upload contacts, craft messages, and dispatch bulk SMS with delivery logs, retries and analytics — designed for speed and reliability.</p>

        <div class="mt-6 flex flex-wrap gap-3">
          <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 bg-indigo-500 hover:bg-indigo-600 rounded-md text-white font-medium">Create account</a>
          <a href="#features" class="inline-flex items-center px-4 py-3 border border-slate-700 rounded-md text-slate-200 hover:bg-slate-700">Explore features</a>
        </div>

        <div class="mt-6 text-sm text-slate-400">No credit card required. Free trial for early users.</div>
      </div>

      <div>
        <div class="bg-gradient-to-br from-slate-700/40 to-slate-900/40 rounded-xl p-6">
          <div class="h-64 bg-slate-800 rounded-lg border border-slate-700 p-4 flex items-center justify-center">
            <div class="text-center">
              <div class="text-slate-300">Live preview</div>
              <div class="mt-3 font-mono text-indigo-400">Campaign: Holiday Offer — 1,204 recipients</div>
              <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                <div class="bg-slate-700/50 p-3 rounded">Sent: <span class="font-semibold text-white">250</span></div>
                <div class="bg-slate-700/50 p-3 rounded">Delivered: <span class="font-semibold text-white">239</span></div>
                <div class="bg-slate-700/50 p-3 rounded">Failed: <span class="font-semibold text-white">11</span></div>
                <div class="bg-slate-700/50 p-3 rounded">Queued: <span class="font-semibold text-white">0</span></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <section id="features" class="mt-14">
      <h2 class="text-2xl font-semibold">Why choose RouteGH</h2>
      <p class="mt-2 text-slate-300 max-w-2xl">Everything you need for reliable messaging: fast imports, dependable delivery, and clear analytics.</p>

      <div class="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-indigo-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Mass import</h3>
              <p class="text-sm text-slate-300">Upload CSV/XLSX and map fields quickly; large files supported.</p>
            </div>
          </div>
        </div>

        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-emerald-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 13a7 7 0 1010 0"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Reliable delivery</h3>
              <p class="text-sm text-slate-300">Retries, backoff and delivery status tracking to keep you informed.</p>
            </div>
          </div>
        </div>

        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-yellow-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V7a4 4 0 118 0v4m-8 4h8"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Analytics</h3>
              <p class="text-sm text-slate-300">Quick insights on sends, deliveries and campaign performance.</p>
            </div>
          </div>
        </div>

        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-pink-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Segmentation</h3>
              <p class="text-sm text-slate-300">Group contacts and target the right audience for each campaign.</p>
            </div>
          </div>
        </div>

        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-sky-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Scheduling</h3>
              <p class="text-sm text-slate-300">Plan sends ahead of time and throttle to control delivery rate.</p>
            </div>
          </div>
        </div>

        <div class="p-5 bg-slate-800/60 rounded-lg border border-slate-700">
          <div class="flex items-start space-x-3">
            <div class="p-2 bg-violet-500 rounded-md text-white">
              <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
              <h3 class="font-semibold">Compliance</h3>
              <p class="text-sm text-slate-300">Tools to help manage opt-outs and message consent.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="mt-16 border-t border-slate-700 pt-6 text-sm text-slate-400">
      <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>© {{ date('Y') == 2025 ? '2025' : '2025 - ' . date('Y') }} {{ config('app.name', 'BulkSMS Pro') }} — Built with Love & Good Taste</div>
        <div class="flex items-center space-x-4">
          <a href="{{ route('docs') }}" class="hover:text-white">Docs</a>
          <a href="{{ route('contact.index') }}" class="hover:text-white">Support</a>
          <a href="#" class="hover:text-white">Privacy</a>
        </div>
      </div>
    </footer>
  </div>
</div>

@include('components.toast')

</x-app-layout>
























