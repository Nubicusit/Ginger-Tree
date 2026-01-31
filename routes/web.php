<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\SalesController;
Route::get('/', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

Route::get('/sales/dashboard', [SalesController::class, 'dashboard'])
    ->name('sales.dashboard')
    ->middleware('auth');

Route::get('/sales/leads', [SalesController::class, 'leads'])
    ->name('sales.leads')
    ->middleware('auth');


Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');



// Route::get('/customers', function () {
//     return view('pages.CustomerManagement');
// })->name('customers');

Route::get('/leads', [AdminController::class, 'leads'])->name('leads');
/* Create Lead */
Route::get('/leads/create', [AdminController::class, 'create'])
    ->name('leads.create');

/* Store Lead */
Route::post('/leads', [AdminController::class, 'store'])
    ->name('leads.store');

/* Edit Lead */
Route::get('/leads/{lead}/edit', [AdminController::class, 'edit'])
    ->name('leads.edit');

/* Update Lead */
Route::put('/leads/{lead}', [AdminController::class, 'update'])
    ->name('leads.update');

/* Delete Lead */
Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])
    ->name('leads.destroy');

Route::post('/marketing/store', [MarketingController::class, 'store'])
    ->name('marketing.store');

Route::get('/lead/{id}', [AdminController::class, 'getLead']);

Route::post('/leads/{lead}/assign', [AdminController::class, 'assign'])->name('leads.assign');

Route::post('/marketing-store', [AdminController::class, 'storeMarketing'])->name('marketing.store');

Route::get('/leads/{lead}', [SalesController::class, 'showJson']);

Route::post('/tasks/store', [SalesController::class, 'store'])
    ->name('tasks.store');

Route::post('/tasks/{id}/update', [SalesController::class, 'updateTask']);
Route::get('/leads/{lead}/tasks', [SalesController::class, 'leadTasks']);
Route::delete('/tasks/{id}', [SalesController::class, 'deleteTask']);
Route::put('/tasks/{id}', [SalesController::class, 'updateTask']);
Route::put('/leads/{lead}/update', [SalesController::class, 'update']);

// Route::get('/leads', function () {
//     return view('pages.LeadEnquiries');
// })->name('leads');

