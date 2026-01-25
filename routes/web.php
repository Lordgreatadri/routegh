<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserApprovalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Documentation route
Route::get('/docs', function () {
    return view('docs');
})->name('docs');

// Contact/Support routes
Route::get('/contact', [\App\Http\Controllers\ContactMessageController::class, 'index'])->name('contact.index');
Route::post('/contact', [\App\Http\Controllers\ContactMessageController::class, 'store'])->name('contact.store');

// Pending approval page
Route::get('/pending-approval', function () {
    return view('auth.pending');
})->name('pending');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->is_client && $user->phone_verified_at && $user->isApproved()) {
        return redirect()->route('users.dashboard');
    }

    return view('dashboard');
})->middleware(['auth', 'phone.verified', 'approved'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes for user approvals and dashboard
Route::prefix('admin')->name('admin.')->middleware(['auth','phone.verified'])->group(function () {
        // Admin sender id management
        Route::resource('sender-ids', \App\Http\Controllers\Admin\SenderIdController::class)->names('sender-ids');
    Route::get('/users/pending', [UserApprovalController::class, 'pending'])->name('users.pending');
    Route::get('/users', [UserApprovalController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    Route::delete('/users/{user}', [UserApprovalController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/role', [UserApprovalController::class, 'assignRole'])->name('users.assignRole');
    // Admin dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    // Resourceful admin sections
    Route::resource('roles', \App\Http\Controllers\Admin\RolesController::class)->names('roles');
    Route::get('roles/{role}/permissions', [\App\Http\Controllers\Admin\RolesController::class, 'permissions'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [\App\Http\Controllers\Admin\RolesController::class, 'updatePermissions'])->name('roles.updatePermissions');
    
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionsController::class)->names('permissions');
    Route::resource('messages', \App\Http\Controllers\Admin\MessagesController::class)->names('messages');
    Route::resource('contacts', \App\Http\Controllers\Admin\ContactsController::class)->names('contacts');
    Route::get('contacts-upload', [\App\Http\Controllers\Admin\ContactsController::class, 'uploadForm'])->name('contacts.uploadForm');
    Route::post('contacts/upload', [\App\Http\Controllers\Admin\ContactsController::class, 'upload'])->name('contacts.upload');
    Route::resource('contact-groups', \App\Http\Controllers\Admin\ContactGroupsController::class)->names('contact-groups');
    Route::resource('campaigns', \App\Http\Controllers\Admin\CampaignsController::class)->names('campaigns');
    Route::resource('uploads', \App\Http\Controllers\Admin\UploadsController::class)->only(['index', 'show', 'destroy'])->names('uploads');
    Route::resource('api-logs', \App\Http\Controllers\Admin\ApiLogsController::class)->only(['index', 'show'])->names('api-logs');
    Route::post('/api-logs/clear', [\App\Http\Controllers\Admin\ApiLogsController::class, 'clearLogs'])->name('api-logs.clear');
    Route::get('/admin-messages', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'index'])->name('admin-messages.index');
    Route::get('/admin-messages/compose', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'compose'])->name('admin-messages.compose');
    Route::post('/admin-messages', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'store'])->name('admin-messages.store');
    Route::get('/admin-messages/{user}', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'show'])->name('admin-messages.show');
    Route::post('/admin-messages/clear', [\App\Http\Controllers\Admin\AdminMessagesController::class, 'clearMessages'])->name('admin-messages.clear');
    
    // Contact Messages (public contact form submissions)
    Route::get('/contact-messages', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'show'])->name('contact-messages.show');
    Route::post('/contact-messages/{contactMessage}/reply', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'reply'])->name('contact-messages.reply');
    Route::patch('/contact-messages/{contactMessage}/status', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'updateStatus'])->name('contact-messages.update-status');
    Route::delete('/contact-messages/{contactMessage}', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'destroy'])->name('contact-messages.destroy');
    Route::delete('/contact-messages-bulk/delete', [\App\Http\Controllers\Admin\ContactMessagesController::class, 'bulkDelete'])->name('contact-messages.bulk-delete');
    
    // System Logs Viewer
    Route::get('/logs', [\App\Http\Controllers\Admin\LogViewerController::class, 'index'])->name('logs.index');
    Route::delete('/logs/clear', [\App\Http\Controllers\Admin\LogViewerController::class, 'clear'])->name('logs.clear');
});

require __DIR__.'/auth.php';

// Phone verification routes
Route::middleware('auth')->group(function () {
    Route::get('/verify-phone', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'showVerifyForm'])->name('phone.verify');
    Route::post('/verify-phone', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'verifyPhone'])->name('phone.verify.post')->middleware('throttle:6,1');
    Route::post('/verify-phone/resend', [\App\Http\Controllers\Auth\RegisteredUserController::class, 'resendOtp'])->name('phone.resend')->middleware('throttle:3,1');
});

// User area routes
Route::middleware(['auth', 'phone.verified'])->group(function () {
    Route::get('/users/dashboard', [\App\Http\Controllers\User\UserDashboardController::class, 'index'])
        ->middleware('senderid.exists')
        ->name('users.dashboard');

    // Sender IDs CRUD for users
    Route::resource('users/sender-ids', \App\Http\Controllers\User\SenderIdController::class, [
        'names' => [
            'index' => 'users.sender-ids.index',
            'create' => 'users.sender-ids.create',
            'store' => 'users.sender-ids.store',
            'edit' => 'users.sender-ids.edit',
            'update' => 'users.sender-ids.update',
            'destroy' => 'users.sender-ids.destroy',
        ]
    ]);
    // User uploads history (index only)
    Route::get('uploads', [\App\Http\Controllers\User\UploadController::class, 'index'])->name('uploads.index');
    // User-scoped contacts controller (dark-mode views)
    Route::resource('contacts', \App\Http\Controllers\User\ContactController::class);
    Route::post('contacts/upload', [\App\Http\Controllers\User\ContactController::class, 'upload'])->name('contacts.upload');
    // Contact groups for users
    Route::resource('contact-groups', \App\Http\Controllers\User\ContactGroupController::class);
    Route::resource('campaigns', \App\Http\Controllers\SmsCampaignController::class);

    // User messages
    Route::resource('messages', \App\Http\Controllers\User\MessageController::class)->only(['index','show']);
    Route::resource('sms-messages', \App\Http\Controllers\User\SmsMessageController::class)->names('sms-messages')->only(['index','show']);
    
    // User's contact/support messages (admin conversation)
    Route::get('support', [\App\Http\Controllers\User\UserMessagesController::class, 'index'])->name('user-messages.index');
    Route::post('support', [\App\Http\Controllers\User\UserMessagesController::class, 'store'])->name('user-messages.store');
    
    // User's contact messages (legacy)
    Route::get('my-messages', [\App\Http\Controllers\User\MyContactMessagesController::class, 'index'])->name('my-messages.index');
    Route::get('my-messages/{contactMessage}', [\App\Http\Controllers\User\MyContactMessagesController::class, 'show'])->name('my-messages.show');
});

// Dev-only OTP viewer
Route::get('/dev/user-otps', function (\Illuminate\Http\Request $request) {
    if (! (app()->isLocal() || config('app.debug'))) {
        abort(404);
    }

    $source = $request->query('source', 'db');
    $limit = (int) $request->query('limit', 50);
    $matches = [];
    $rows = collect();

    if ($source === 'log') {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $lines = array_slice(array_filter(explode("\n", file_get_contents($logPath))), -$limit);
            foreach ($lines as $line) {
                if (preg_match('/\[SMS\]\[OTP\] to\s+([0-9+\-\s]+):\s*(\d{4,8})/i', $line, $m)) {
                    $matches[] = ['phone' => trim($m[1]), 'otp' => trim($m[2]), 'line' => $line];
                }
            }
        }
    } else {
        $rows = \App\Models\UserOtp::orderByDesc('created_at')->limit($limit)->get();
    }

    return view('dev.user-otps', ['source' => $source, 'limit' => $limit, 'matches' => $matches, 'rows' => $rows]);
})->name('dev.userotps');
