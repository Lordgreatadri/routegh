<x-app-layout>
<div class="min-h-screen bg-gradient-to-b from-slate-900 to-slate-800 text-slate-100">
  <div class="max-w-7xl mx-auto px-6 py-16">
    <!-- Header -->
    <div class="text-center mb-12">
      <h1 class="text-4xl sm:text-5xl font-extrabold leading-tight">Get in Touch</h1>
      <p class="mt-4 text-slate-300 max-w-2xl mx-auto">Have questions or need support? We're here to help. Send us a message and we'll respond as soon as possible.</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
      <!-- Contact Information -->
      <div class="lg:col-span-1 space-y-6">
        <div class="bg-slate-800/60 rounded-xl border border-slate-700 p-6">
          <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            Call Us
          </h3>
          <div class="space-y-2 text-slate-300">
            <a href="tel:+233245309876" class="block hover:text-indigo-400 transition">
              <p class="text-sm text-slate-400">Primary</p>
              <p class="text-lg font-medium">+233 245 309 876</p>
            </a>
            <a href="tel:+233553456400" class="block hover:text-indigo-400 transition mt-3">
              <p class="text-sm text-slate-400">Secondary</p>
              <p class="text-lg font-medium">+233 553 456 400</p>
            </a>
          </div>
        </div>

        <div class="bg-slate-800/60 rounded-xl border border-slate-700 p-6">
          <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Email
          </h3>
          <div class="text-slate-300">
            <a href="mailto:support@routegh.com" class="block hover:text-indigo-400 transition">
              <p class="text-sm text-slate-400">General Inquiries</p>
              <p class="text-lg font-medium">support@routegh.com</p>
            </a>
          </div>
        </div>

        <div class="bg-slate-800/60 rounded-xl border border-slate-700 p-6">
          <h3 class="text-xl font-semibold mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Business Hours
          </h3>
          <div class="text-slate-300 space-y-2">
            <div class="flex justify-between">
              <span class="text-slate-400">Monday - Friday</span>
              <span class="font-medium">9:00 AM - 6:00 PM</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Saturday</span>
              <span class="font-medium">10:00 AM - 4:00 PM</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-400">Sunday</span>
              <span class="font-medium">Closed</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Form -->
      <div class="lg:col-span-2">
        <div class="bg-slate-800/60 rounded-xl border border-slate-700 p-8">
          <h2 class="text-2xl font-bold mb-6">Send us a Message</h2>

          @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-lg text-green-300">
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
              </div>
            </div>
          @endif

          <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-slate-200 mb-2">
                  Full Name <span class="text-red-400">*</span>
                </label>
                <input 
                  type="text" 
                  name="name" 
                  id="name" 
                  value="{{ old('name') }}"
                  required
                  class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                  placeholder="John Doe"
                />
                @error('name')
                  <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="email" class="block text-sm font-medium text-slate-200 mb-2">
                  Email Address <span class="text-red-400">*</span>
                </label>
                <input 
                  type="email" 
                  name="email" 
                  id="email" 
                  value="{{ old('email') }}"
                  required
                  class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                  placeholder="john@example.com"
                />
                @error('email')
                  <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
              <div>
                <label for="phone" class="block text-sm font-medium text-slate-200 mb-2">
                  Phone Number <span class="text-red-400">*</span>
                </label>
                <input 
                  type="tel" 
                  name="phone" 
                  id="phone" 
                  value="{{ old('phone') }}"
                  required
                  class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                  placeholder="020XXXXXXX"
                />
                @error('phone')
                  <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label for="subject" class="block text-sm font-medium text-slate-200 mb-2">
                  Subject <span class="text-red-400">*</span>
                </label>
                <select 
                  name="subject" 
                  id="subject" 
                  required
                  class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                >
                  <option value="">Select a subject</option>
                  <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry</option>
                  <option value="Technical Support" {{ old('subject') == 'Technical Support' ? 'selected' : '' }}>Technical Support</option>
                  <option value="Billing Question" {{ old('subject') == 'Billing Question' ? 'selected' : '' }}>Billing Question</option>
                  <option value="Feature Request" {{ old('subject') == 'Feature Request' ? 'selected' : '' }}>Feature Request</option>
                  <option value="Bug Report" {{ old('subject') == 'Bug Report' ? 'selected' : '' }}>Bug Report</option>
                  <option value="Other" {{ old('subject') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('subject')
                  <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label for="message" class="block text-sm font-medium text-slate-200 mb-2">
                Message <span class="text-red-400">*</span>
              </label>
              <textarea 
                name="message" 
                id="message" 
                rows="6" 
                required
                maxlength="2000"
                class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-slate-100 placeholder-slate-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"
                placeholder="Please describe your inquiry or issue in detail..."
              >{{ old('message') }}</textarea>
              <div class="mt-1 flex justify-between text-xs text-slate-400">
                <span>@error('message')<span class="text-red-400">{{ $message }}</span>@enderror</span>
                <span id="charCount">0 / 2000</span>
              </div>
            </div>

            <div class="flex items-center justify-between pt-4">
              <p class="text-sm text-slate-400">
                <span class="text-red-400">*</span> Required fields
              </p>
              <button 
                type="submit"
                class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-white font-medium transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
              >
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Message
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Back to Home -->
    <div class="mt-12 text-center">
      <a href="{{ route('welcome') }}" class="inline-flex items-center text-indigo-400 hover:text-indigo-300 transition">
        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Home
      </a>
    </div>
  </div>
</div>

@include('components.toast')

<script>
  // Character counter for message textarea
  const messageField = document.getElementById('message');
  const charCount = document.getElementById('charCount');
  
  if (messageField && charCount) {
    messageField.addEventListener('input', function() {
      const count = this.value.length;
      charCount.textContent = `${count} / 2000`;
      
      if (count > 1900) {
        charCount.classList.add('text-yellow-400');
      } else {
        charCount.classList.remove('text-yellow-400');
      }
    });
    
    // Initialize count on page load
    charCount.textContent = `${messageField.value.length} / 2000`;
  }
</script>
</x-app-layout>
