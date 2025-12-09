<?php  

use Illuminate\Support\Facades\Route;

/* -----------------------------
|  Controllers (user area)
|------------------------------*/
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PaymentController; // member billing

/* -----------------------------
|  Controllers (admin area)
|------------------------------*/
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\ApplicationReviewController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;

/* -----------------------------
|  Payment callbacks / webhooks
|------------------------------*/
use App\Http\Controllers\Webhook\FlutterwaveWebhookController;
use App\Http\Controllers\Webhook\MtnMomoWebhookController;

/* -----------------------------
|  Festive Camp
|------------------------------*/
use App\Http\Controllers\FestiveCampController;
use App\Models\FestiveCampRegistration;   // 🔹 add this line

/* -----------------------------
|  Landing / Dashboard
|------------------------------*/
Route::get('/', function () {
    // Festive camp capacity + remaining spots for the welcome page
    $campCapacity = 100;

    // For now: fixed "20 spots remaining". Later you can compute from DB.
    $remaining_spots = 20;

    return view('welcome', compact('remaining_spots', 'campCapacity'));
});

Route::get('/dashboard', function () {
    $userId = auth()->id();

    $festiveCount    = 0;
    $festiveApproved = 0;

    if ($userId) {
        $regs = FestiveCampRegistration::where('user_id', $userId)->get();
        $festiveCount    = $regs->count();
        $festiveApproved = $regs->where('status', 'approved')->count();
    }

    return view('dashboard', compact('festiveCount', 'festiveApproved'));
})->middleware(['auth', 'verified'])->name('dashboard');

/* -----------------------------
|  Authenticated user routes
|------------------------------*/
Route::middleware('auth')->group(function () {

    // Profile (for all logged in users: parents & admins)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* -------------------------
    |  Festive Camp (parent area)
    |--------------------------*/
    Route::get('/festive-camp/register', [FestiveCampController::class, 'create'])
        ->name('festive-camp.register');

    Route::post('/festive-camp/register', [FestiveCampController::class, 'store'])
        ->name('festive-camp.store');

    // Parent view of their own festive camp registrations (can be multiple kids)
    Route::get('/festive-camp/my', [FestiveCampController::class, 'my'])
        ->name('festive-camp.my');

    // ✅ Printable receipt for a specific festive camp registration
    Route::get('/festive-camp/{registration}/receipt', [FestiveCampController::class, 'receipt'])
        ->name('festive-camp.receipt');

    // ✅ QR code image for a specific festive camp registration (used on receipt)
    Route::get('/festive-camp/{registration}/qr', [FestiveCampController::class, 'qr'])
        ->name('festive-camp.qr');

    /* -------------------------
    |  Regular Membership – TEMPORARILY DISABLED
    |--------------------------*/
    /*
    // Applications (create/submit/view)
    Route::get('/application', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/application', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/{app}', [ApplicationController::class, 'show'])->name('application.show');

    // Documents (upload + delete)
    Route::post('/application/{app}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::delete('/documents/{doc}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Billing (member)
    Route::get('/billing', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/billing/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/billing', [PaymentController::class, 'store'])->name('payments.store');

    // Refresh a single payment status (MoMo poll)
    Route::post('/billing/{payment}/refresh', [PaymentController::class, 'refresh'])
        ->name('payments.refresh');

    // Optional return url (used by card/other providers)
    Route::get('/billing/callback', [PaymentController::class, 'callback'])
        ->name('payments.callback');
    */
});

/* ✅ Public verification route – used when scanning QR code on receipt */
Route::get('/festive-camp/verify/{token}', [FestiveCampController::class, 'verify'])
    ->name('festive-camp.verify');

/* -----------------------------
|  Admin area (dashboard)
|------------------------------*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Applications (admin)
        Route::get('/applications', [AdminApplicationController::class, 'index'])->name('apps.index');
        Route::get('/applications/{app}', [AdminApplicationController::class, 'show'])->name('apps.show');
        Route::post('/applications/{app}/approve', [AdminApplicationController::class, 'approve'])->name('apps.approve');
        Route::post('/applications/{app}/reject', [AdminApplicationController::class, 'reject'])->name('apps.reject');

        // Review controller
        Route::get('/review', [ApplicationReviewController::class, 'index'])->name('applications.index');
        Route::get('/review/{application}', [ApplicationReviewController::class, 'show'])->name('applications.show');
        Route::post('/review/{application}/status', [ApplicationReviewController::class, 'updateStatus'])->name('applications.updateStatus');

        // Payments (admin)
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/export', [AdminPaymentController::class, 'export'])->name('payments.export');
        Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/status', [AdminPaymentController::class, 'updateStatus'])->name('payments.updateStatus');

        // Festive camp campers list (admin view)
        Route::get('/festive-camp', [FestiveCampController::class, 'index'])
            ->name('festive-camp.index');

        // Approve a festive camp registration
        Route::post('/festive-camp/{registration}/approve', [FestiveCampController::class, 'approve'])
            ->name('festive-camp.approve');
    });

/* -----------------------------
|  Payment Webhooks (public)
|  — CSRF-exempt via VerifyCsrfToken
|------------------------------*/
Route::post('/webhooks/flutterwave', [FlutterwaveWebhookController::class, 'handle'])
    ->name('webhooks.flutterwave');

Route::post('/webhooks/momo', [MtnMomoWebhookController::class, 'handle'])
    ->name('webhooks.momo');

/* Breeze auth scaffolding */
require __DIR__.'/auth.php';
