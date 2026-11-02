<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

use Carbon\Carbon;

// ==========================================
// 1. PUBLIC ROUTES (Anyone can access)
// ==========================================
Route::get('/', function () {
    $services = \App\Models\Service::all();
    return view('home', compact('services'));
});
// Add this under your public routes
Route::get('/get-busy-slots', [AppointmentController::class, 'getBusySlots'])->name('slots.busy');
// Customer Booking & Tracking
Route::get('/book', [AppointmentController::class, 'customerCreate'])->name('customer.book');
Route::post('/book', [AppointmentController::class, 'store'])->name('customer.store');
Route::get('/find-appointment', [AppointmentController::class, 'findPage'])->name('appointment.find');
Route::post('/my-appointments', [AppointmentController::class, 'search'])->name('appointment.search');
Route::get('/my-appointments/{phone}', [AppointmentController::class, 'showByPhone'])->name('appointment.list');

// Customer Reschedule/Cancel (Public but usually requires phone search first)
Route::get('/appointment/{id}/edit', [AppointmentController::class, 'edit'])->name('appointment.edit');
Route::put('/appointment/{id}/update', [AppointmentController::class, 'update'])->name('appointment.update');
Route::delete('/appointment/{id}/cancel', [AppointmentController::class, 'destroy'])->name('appointment.cancel');
Route::get('/test-sms', function () {
    $controller = new \App\Http\Controllers\AppointmentController();
    // Use 'sendMessage' to match the updated function in your controller
    $sent = $controller->sendMessage('09162138833', 'Hello! This is a test SMS from PhilSMS.');
    return $sent ? 'SMS request sent! Check your PhilSMS dashboard.' : 'SMS failed. Check logs.';
});


// ==========================================
// 2. PROTECTED ROUTES (Must be Logged In)
// ==========================================
Route::middleware(['auth'])->group(function () {

    Route::post('/settings/operational', [AdminController::class, 'updateOperational'])->name('settings.update-operational');
    Route::post('/settings/info', [AdminController::class, 'updateInfo'])->name('settings.update-info');
    Route::post('/settings/holiday', [AdminController::class, 'addHoliday'])->name('settings.add-holiday');
    Route::delete('/settings/holiday/{date}', [AdminController::class, 'removeHoliday'])->name('settings.remove-holiday');

    // Add this inside your auth middleware group
    Route::get('/customer-history/{name}', [CustomerController::class, 'getCustomerHistory']);
    // Profile Management (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard & Viewing (Admin & Staff)
    // Dashboard & Viewing (Both Admin & Staff can SEE this)
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::get('/admin', [\App\Http\Controllers\DashboardController::class, 'index'])->name('admin.dashboard');

        // These are also "view only" lists
        // Temporary change to test
        // If you changed the name to staff.list
        Route::get('/staff-list', [StaffController::class, 'index'])->name('staff.list');
        Route::get('/payment', [AppointmentController::class, 'paymentList'])->name('payment.index');
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/calendar', [AppointmentController::class, 'index']);
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        // Use this route for the final action on the dashboard
        Route::post('/appointments/{id}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    });

    // ADMIN ONLY ACTIONS (CRUD)
    Route::middleware(['role:admin'])->group(function () {

        // Change {id} to {staff} to match Laravel's default expectations
        Route::put('/staff-list/{staff}', [StaffController::class, 'update'])->name('staff.update');
        Route::post('/staff-list', [StaffController::class, 'store'])->name('staff.store');
        Route::delete('/staff-list/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
        // Use these four lines. Don't use Route::resource if you want full control.
        Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
        Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
        Route::put('/services/{id}', [ServiceController::class, 'update'])->name('services.update');
        Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('/payment', [AppointmentController::class, 'storePayment'])->name('payment.store');
        Route::post('/appointments/store', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::post('/appointments/{id}/pay', [AppointmentController::class, 'markAsPaid'])->name('appointments.pay');
        Route::post('/appointments/{id}/send-reminder', [AppointmentController::class, 'sendManualReminder'])->name('admin.sendReminder');
        // NEW ADMIN SETTINGS ROUTES
        Route::get('/settings', [App\Http\Controllers\AdminController::class, 'settings'])->name('admin.settings');
        Route::post('/settings/categories', [App\Http\Controllers\AdminController::class, 'storeCategory'])->name('categories.store');
        Route::delete('/settings/categories/{id}/archive', [AdminController::class, 'archiveCategory'])->name('categories.archive');
        Route::patch('/settings/categories/{id}/restore', [AdminController::class, 'restoreCategory'])->name('categories.restore');
        Route::post('/settings/operational', [AdminController::class, 'updateOperational'])->name('settings.update-operational');
        Route::post('/settings/hours', [AdminController::class, 'updateHours'])->name('settings.update-hours');
        Route::post('/settings/holidays', [AdminController::class, 'addHoliday'])->name('settings.add-holiday');
        // Holiday Archive/Restore Routes
        Route::post('/settings/holidays/{date}/archive', [AdminController::class, 'archiveHoliday'])->name('settings.archive-holiday');
        Route::post('/settings/holidays/{date}/restore', [AdminController::class, 'restoreHoliday'])->name('settings.restore-holiday');
        Route::patch('/appointments/{id}/status/{status}', [AppointmentController::class, 'updateStatus'])->name('appointment.status');

        // Also, add the route to actually load the page
        Route::patch('/appointments/{id}/status/{approval_status}', [AppointmentController::class, 'updateStatus'])->name('appointment.approval_status');

        // 2. Reservation Page
        Route::get('/reservation', [AppointmentController::class, 'showReservations'])->name('admin.reservations');
        Route::put('/staff-list/archive/{id}', [StaffController::class, 'archive']);
        Route::patch('/staff/restore/{id}', [AdminController::class, 'restoreStaff'])->name('staff.restore');
        Route::patch('/services/restore/{id}', [ServiceController::class, 'restore'])->name('services.restore');
    });
    // Move this outside the middleware group temporarily

});

require __DIR__ . '/auth.php';
