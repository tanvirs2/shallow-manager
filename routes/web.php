<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PumpOwnerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WaterEntryController;
use Illuminate\Support\Facades\Route;

// ── Admin Login (no middleware) ───────────────────────────────────────────────
Route::get('/admin-login',  [AdminLoginController::class, 'showLogin'])->name('admin.login');
Route::post('/admin-login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin-logout',[AdminLoginController::class, 'logout'])->name('admin.logout');

// ── Admin Panel (admin middleware handles auth + is_admin check) ──────────────
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/',                                   [AdminController::class, 'index'])->name('dashboard');
    Route::get('/users',                              [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',                       [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                             [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{user}/edit',                  [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}',                       [AdminController::class, 'updateUser'])->name('users.update');
    Route::post('/users/{user}/grant',                [AdminController::class, 'grantAccess'])->name('users.grant');
    Route::post('/users/{user}/revoke',               [AdminController::class, 'revokeAccess'])->name('users.revoke');
    Route::delete('/users/{user}',                    [AdminController::class, 'deleteUser'])->name('users.delete');
});

// ── Auth routes (no middleware) ───────────────────────────────────────────────
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth', 'subscription'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [PumpOwnerController::class, 'edit'])->name('pump-owner.edit');
    Route::put('/profile', [PumpOwnerController::class, 'update'])->name('pump-owner.update');

    Route::resource('farmers', FarmerController::class);

    Route::resource('water-entries', WaterEntryController::class);

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/farmer-due', [PaymentController::class, 'farmerDue'])->name('payments.farmer-due');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::get('/invoices/{waterEntry}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{waterEntry}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get('/farmers/{farmer}/bill', [InvoiceController::class, 'farmerBill'])->name('invoices.farmer-bill');

    Route::get('/import', [ImportController::class, 'index'])->name('import.index');
    Route::post('/import/farmers', [ImportController::class, 'farmers'])->name('import.farmers');
    Route::post('/import/water-entries', [ImportController::class, 'waterEntries'])->name('import.water-entries');
    Route::get('/import/template/{type}', [ImportController::class, 'template'])->name('import.template');

});
