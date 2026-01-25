<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900">
        <!-- Header -->
        <div class="bg-slate-800/50 border-b border-slate-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-white mb-2">RouteGH Documentation</h1>
                    <p class="text-lg text-slate-300">Complete guide to using our SMS messaging platform</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar Navigation -->
                <div class="lg:col-span-1">
                    <div class="sticky top-4 bg-slate-800 rounded-lg p-6 border border-slate-700">
                        <h3 class="text-lg font-semibold text-white mb-4">Quick Navigation</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-slate-300 hover:text-white transition-colors">Overview</a>
                            <a href="#getting-started" class="block text-slate-300 hover:text-white transition-colors">Getting Started</a>
                            <a href="#user-features" class="block text-slate-300 hover:text-white transition-colors">User Features</a>
                            <a href="#admin-features" class="block text-slate-300 hover:text-white transition-colors">Admin Features</a>
                            <a href="#user-journey" class="block text-slate-300 hover:text-white transition-colors">User Journey</a>
                            <a href="#support" class="block text-slate-300 hover:text-white transition-colors">Support</a>
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-3 space-y-12">
                    <!-- Overview Section -->
                    <section id="overview" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-4">Overview</h2>
                        <p class="text-slate-300 mb-4">
                            RouteGH is a comprehensive SMS messaging platform designed to help businesses and organizations manage their communication efficiently. Our platform provides powerful tools for contact management, group messaging, campaign scheduling, and detailed analytics.
                        </p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="bg-slate-700/50 rounded-lg p-4">
                                <div class="text-blue-400 text-2xl mb-2">🚀</div>
                                <h4 class="text-white font-semibold mb-1">Fast & Reliable</h4>
                                <p class="text-sm text-slate-400">Send messages instantly with high delivery rates</p>
                            </div>
                            <div class="bg-slate-700/50 rounded-lg p-4">
                                <div class="text-green-400 text-2xl mb-2">📊</div>
                                <h4 class="text-white font-semibold mb-1">Analytics</h4>
                                <p class="text-sm text-slate-400">Track message delivery and campaign performance</p>
                            </div>
                            <div class="bg-slate-700/50 rounded-lg p-4">
                                <div class="text-purple-400 text-2xl mb-2">🔒</div>
                                <h4 class="text-white font-semibold mb-1">Secure</h4>
                                <p class="text-sm text-slate-400">Your data is protected with enterprise-grade security</p>
                            </div>
                        </div>
                    </section>

                    <!-- Getting Started Section -->
                    <section id="getting-started" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-6">Getting Started</h2>
                        
                        <div class="space-y-6">
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Step 1: Registration</h3>
                                <p class="text-slate-300 mb-2">Create your account by providing:</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-1 ml-4">
                                    <li>Full name and email address</li>
                                    <li>Valid phone number</li>
                                    <li>Secure password</li>
                                </ul>
                            </div>

                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Step 2: Phone Verification</h3>
                                <p class="text-slate-300 mb-2">Verify your phone number:</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-1 ml-4">
                                    <li>Receive a 4-digit OTP code via SMS</li>
                                    <li>Enter the code to verify your number</li>
                                    <li>Request a new code if needed (limited to prevent abuse)</li>
                                </ul>
                            </div>

                            <div class="border-l-4 border-yellow-500 pl-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Step 3: Account Approval</h3>
                                <p class="text-slate-300 mb-2">Wait for admin approval:</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-1 ml-4">
                                    <li>Your account will be reviewed by our admin team</li>
                                    <li>You'll receive an email notification once approved</li>
                                    <li>Login and start using the platform</li>
                                </ul>
                            </div>

                            <div class="border-l-4 border-purple-500 pl-4">
                                <h3 class="text-xl font-semibold text-white mb-2">Step 4: Start Messaging</h3>
                                <p class="text-slate-300 mb-2">Begin your messaging journey:</p>
                                <ul class="list-disc list-inside text-slate-400 space-y-1 ml-4">
                                    <li>Upload your contacts or add them manually</li>
                                    <li>Create contact groups for organized messaging</li>
                                    <li>Send individual messages or create campaigns</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- User Features Section -->
                    <section id="user-features" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-6">User Features</h2>
                        
                        <div class="space-y-8">
                            <!-- Dashboard -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-blue-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Dashboard</h3>
                                        <p class="text-slate-300 mb-3">Your central hub for all messaging activities.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>View total contacts, groups, campaigns, and messages</li>
                                            <li>Track message status (queued, sent, delivered, failed)</li>
                                            <li>Monitor recent activity and quick stats</li>
                                            <li>Access recent uploads and campaigns</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Management -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-green-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Contact Management</h3>
                                        <p class="text-slate-300 mb-3">Organize and manage your contacts efficiently.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Add Contacts:</strong> Manually add individual contacts with name and phone number</li>
                                            <li><strong class="text-slate-300">Bulk Upload:</strong> Import contacts from Excel (.xlsx) or CSV files</li>
                                            <li><strong class="text-slate-300">Edit & Delete:</strong> Update contact information or remove contacts</li>
                                            <li><strong class="text-slate-300">Search & Filter:</strong> Quickly find contacts using search functionality</li>
                                            <li><strong class="text-slate-300">View Details:</strong> See contact information and message history</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Groups -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-purple-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Contact Groups</h3>
                                        <p class="text-slate-300 mb-3">Organize contacts into groups for targeted messaging.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Create Groups:</strong> Set up groups with name and description</li>
                                            <li><strong class="text-slate-300">Assign Contacts:</strong> Add multiple contacts to groups</li>
                                            <li><strong class="text-slate-300">Manage Members:</strong> View and update group membership</li>
                                            <li><strong class="text-slate-300">Group Messaging:</strong> Send messages to entire groups at once</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Campaigns -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-orange-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">SMS Campaigns</h3>
                                        <p class="text-slate-300 mb-3">Create and manage bulk messaging campaigns.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Campaign Creation:</strong> Set up campaigns with title and message content</li>
                                            <li><strong class="text-slate-300">Select Recipients:</strong> Choose from contacts or entire groups</li>
                                            <li><strong class="text-slate-300">Schedule Messages:</strong> Send immediately or schedule for later</li>
                                            <li><strong class="text-slate-300">Track Progress:</strong> Monitor campaign status and delivery</li>
                                            <li><strong class="text-slate-300">Campaign History:</strong> View past campaigns and their results</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages & Tracking -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-yellow-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Messages & Tracking</h3>
                                        <p class="text-slate-300 mb-3">Monitor all your sent messages and their status.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Message History:</strong> View all sent messages with timestamps</li>
                                            <li><strong class="text-slate-300">Delivery Status:</strong> Track queued, sent, delivered, and failed messages</li>
                                            <li><strong class="text-slate-300">Message Details:</strong> See recipient, content, and delivery time</li>
                                            <li><strong class="text-slate-300">Filter & Search:</strong> Find specific messages easily</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Uploads History -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-cyan-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Uploads History</h3>
                                        <p class="text-slate-300 mb-3">Keep track of all your contact file uploads.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Upload Records:</strong> View all uploaded files with dates</li>
                                            <li><strong class="text-slate-300">Import Summary:</strong> See how many contacts were imported</li>
                                            <li><strong class="text-slate-300">File Information:</strong> Check original filename and upload status</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Support Messaging -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-pink-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Support Messaging</h3>
                                        <p class="text-slate-300 mb-3">Communicate directly with platform administrators.</p>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li><strong class="text-slate-300">Send Messages:</strong> Contact admin for help or questions</li>
                                            <li><strong class="text-slate-300">View Conversation:</strong> See all messages with admin</li>
                                            <li><strong class="text-slate-300">Real-time Updates:</strong> Get notified of admin responses</li>
                                            <li><strong class="text-slate-300">Message Status:</strong> Track if your message has been read or replied to</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Admin Features Section -->
                    <section id="admin-features" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-6">Admin Features</h2>
                        <p class="text-slate-300 mb-6">Platform administrators have access to additional tools for system management and oversight.</p>
                        
                        <div class="space-y-8">
                            <!-- User Management -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-blue-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">User Management</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>Review and approve pending user registrations</li>
                                            <li>Reject users with reasons (email notification sent)</li>
                                            <li>Assign roles and permissions to users</li>
                                            <li>View all users with status filtering</li>
                                            <li>Delete user accounts when necessary</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- System Monitoring -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-green-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">System Dashboard & Metrics</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>View total users, pending approvals, and active accounts</li>
                                            <li>Monitor message statistics (total, queued, sent, delivered, failed)</li>
                                            <li>Track daily system metrics (messages, uploads, recipients)</li>
                                            <li>View 7-day trends for messages and uploads</li>
                                            <li>Monitor API call usage and performance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact & Campaign Management -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-purple-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Contact & Campaign Oversight</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>View all system contacts and groups</li>
                                            <li>Monitor all user campaigns and their status</li>
                                            <li>Access detailed message delivery reports</li>
                                            <li>Manage contact uploads across all users</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Roles & Permissions -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-yellow-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Roles & Permissions</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>Create and manage user roles (Admin, User, Manager, etc.)</li>
                                            <li>Define custom permissions for different actions</li>
                                            <li>Assign permissions to roles</li>
                                            <li>Control access to features based on roles</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- API Logs -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-orange-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">API Logs</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>Monitor all API requests and responses</li>
                                            <li>Track SMS delivery status via API logs</li>
                                            <li>View detailed request/response data</li>
                                            <li>Clear old logs by date range for maintenance</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Support Messages -->
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="bg-pink-500/20 p-3 rounded-lg mr-4">
                                        <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-xl font-semibold text-white mb-2">Support Messages</h3>
                                        <ul class="list-disc list-inside text-slate-400 space-y-1">
                                            <li>View all user support conversations</li>
                                            <li>Respond to user inquiries and questions</li>
                                            <li>Filter unread, read, and replied messages</li>
                                            <li>Send proactive messages to users</li>
                                            <li>Clear old message threads by date</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- User Journey Section -->
                    <section id="user-journey" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-6">User Journey</h2>
                        <p class="text-slate-300 mb-6">A step-by-step guide through the typical user experience on RouteGH.</p>
                        
                        <div class="space-y-4">
                            <!-- Step 1 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">1</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Sign Up</h4>
                                    <p class="text-slate-300">Visit the registration page and create your account with name, email, phone, and password.</p>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">2</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Verify Phone</h4>
                                    <p class="text-slate-300">Receive a 4-digit OTP code via SMS and enter it to verify your phone number. You can request a new code if needed.</p>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold">3</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Wait for Approval</h4>
                                    <p class="text-slate-300">Your account will be reviewed by our admin team. You'll see a "Pending Approval" page and receive an email once approved or rejected.</p>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">4</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Login & Explore Dashboard</h4>
                                    <p class="text-slate-300">Once approved, login to access your personalized dashboard showing all your messaging statistics and recent activity.</p>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-pink-500 rounded-full flex items-center justify-center text-white font-bold">5</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Add Contacts</h4>
                                    <p class="text-slate-300">Navigate to Contacts and either add individual contacts manually or upload a CSV/Excel file with multiple contacts.</p>
                                </div>
                            </div>

                            <!-- Step 6 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold">6</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Create Groups</h4>
                                    <p class="text-slate-300">Organize your contacts into groups (e.g., "Customers", "Team", "Marketing") for easier targeting.</p>
                                </div>
                            </div>

                            <!-- Step 7 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold">7</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Create Campaign</h4>
                                    <p class="text-slate-300">Go to Campaigns, create a new campaign with your message, select recipients (contacts or groups), and choose to send now or schedule for later.</p>
                                </div>
                            </div>

                            <!-- Step 8 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-red-500 rounded-full flex items-center justify-center text-white font-bold">8</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Monitor Messages</h4>
                                    <p class="text-slate-300">Track your message delivery in the Messages section. See queued, sent, delivered, and failed statuses in real-time.</p>
                                </div>
                            </div>

                            <!-- Step 9 -->
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold">9</div>
                                <div class="flex-1 bg-slate-700/30 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-white mb-2">Get Support</h4>
                                    <p class="text-slate-300">Need help? Visit the Support section to send messages directly to administrators. They'll respond to your questions and concerns.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Support Section -->
                    <section id="support" class="bg-slate-800 rounded-lg p-8 border border-slate-700">
                        <h2 class="text-3xl font-bold text-white mb-6">Support & Help</h2>
                        
                        <div class="space-y-6">
                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-3">Contact Support</h3>
                                <p class="text-slate-300 mb-4">Need assistance? Our support team is here to help.</p>
                                <a href="{{ route('contact.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Contact Us
                                </a>
                            </div>

                            <div class="bg-slate-700/30 rounded-lg p-6">
                                <h3 class="text-xl font-semibold text-white mb-3">Frequently Asked Questions</h3>
                                <div class="space-y-4">
                                    <div>
                                        <h4 class="text-white font-semibold mb-1">How long does account approval take?</h4>
                                        <p class="text-slate-400">Account approval typically takes 24-48 hours. You'll receive an email notification once your account is reviewed.</p>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-semibold mb-1">What file formats are supported for contact uploads?</h4>
                                        <p class="text-slate-400">We support Excel (.xlsx) and CSV (.csv) files. Your file should have columns for name and phone number.</p>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-semibold mb-1">Can I schedule messages for later?</h4>
                                        <p class="text-slate-400">Yes! When creating a campaign, you can choose to send immediately or schedule for a specific date and time.</p>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-semibold mb-1">How do I know if my message was delivered?</h4>
                                        <p class="text-slate-400">Check the Messages section to see real-time delivery status. Messages show as queued, sent, delivered, or failed.</p>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-semibold mb-1">What should I do if I forgot my password?</h4>
                                        <p class="text-slate-400">Use the "Forgot Password" link on the login page to reset your password via email.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-blue-600/20 to-purple-600/20 rounded-lg p-6 border border-blue-500/30">
                                <h3 class="text-xl font-semibold text-white mb-2">Need More Help?</h3>
                                <p class="text-slate-300 mb-4">If you're logged in, you can use the Support Messages feature to chat directly with our admin team for personalized assistance.</p>
                                <div class="flex items-center text-sm text-slate-400">
                                    <svg class="w-5 h-5 mr-2 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Available 24/7 for logged-in users
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Back to Top -->
                    <div class="text-center mt-12">
                        <a href="#overview" class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            Back to Top
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-16 border-t border-slate-700 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 text-center sm:text-left">
                    <div class="text-slate-400">© {{ date('Y') == 2025 ? '2025' : '2025 - ' . date('Y') }} {{ config('app.name', 'BulkSMS Pro') }} — Built with Love & Good Taste</div>
                    <div class="flex items-center justify-center space-x-4">
                        <a href="{{ route('welcome') }}" class="text-slate-400 hover:text-white transition-colors">Home</a>
                        <a href="{{ route('contact.index') }}" class="text-slate-400 hover:text-white transition-colors">Support</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>