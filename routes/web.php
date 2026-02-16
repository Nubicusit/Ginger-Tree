<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SiteVisitController;
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// sales executive
Route::get('/sales/dashboard', [SalesController::class, 'dashboard'])->middleware('auth')->name('sales_executive.dashboard');
Route::get('/sales/leads', [SalesController::class, 'leads'])->name('sales.leads')->middleware('auth');
Route::get('/sales/sitevisits', [SalesController::class, 'sitevisit'])->name('sales.sitevisit')->middleware('auth');
Route::get('/leads/{lead}', [SalesController::class, 'showJson']);
Route::post('/tasks/store', [SalesController::class, 'store'])->name('tasks.store');
Route::post('/tasks/{id}/update', [SalesController::class, 'updateTask']);
Route::get('/leads/{lead}/tasks', [SalesController::class, 'leadTasks']);
Route::delete('/tasks/{id}', [SalesController::class, 'deleteTask']);
Route::put('/tasks/{id}', [SalesController::class, 'updateTask']);
Route::put('/leads/{lead}/update', [SalesController::class, 'update']);
Route::get('/users/create', [SalesController::class, 'create'])->name('users.create');

//customer-admin
Route::get('/customers', [CustomerController::class, 'index'])->middleware(['auth', 'admin'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');

//admin dashboard
Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');
Route::get('/leads', [AdminController::class, 'leads'])->middleware(['auth', 'admin'])->name('leads');
Route::get('/leads/create', [AdminController::class, 'create'])->middleware(['auth', 'admin'])->name('leads.create');
Route::post('/leads', [AdminController::class, 'store'])->middleware(['auth', 'admin'])->name('leads.store');
Route::get('/leads/{lead}/edit', [AdminController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [AdminController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])->name('leads.destroy');
Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);

Route::get('/useraccounts', [AdminController::class, 'useraccounts'])->middleware(['auth', 'admin'])->name('useraccounts');
Route::post('/useraccounts/store', [AdminController::class, 'storeUser'])->middleware(['auth', 'admin'])->name('users.store');
Route::post('/departments/store', [AdminController::class, 'storeDepartment'])->middleware(['auth','admin'])->name('departments.store');
Route::delete('/useraccounts/{user}', [AdminController::class, 'destroyUser'])->middleware(['auth', 'admin'])->name('users.destroy');

// Route::post('/marketing/store', [MarketingController::class, 'store'])->name('marketing.store');

Route::get('/lead/{id}', [AdminController::class, 'getLead']);
Route::post('/leads/{lead}/assign', [AdminController::class, 'assign'])->middleware(['auth', 'admin'])->name('leads.assign');
Route::post('/marketing-store', [AdminController::class, 'storeMarketing'])->name('admin.marketing.store');

//sitevisit
Route::post('/leads/{lead}/site-visit', [SiteVisitController::class, 'saveSiteVisit']);
Route::post('/sale-executive/site-visit/update',[SiteVisitController::class, 'storeOrUpdate'])->name('sitevisit.update');
Route::get('/sale-executive/site-visit/{lead}',[SiteVisitController::class, 'show'])->name('sitevisit.show');
Route::post('/sale-executive/site-visit/delete-file',[SiteVisitController::class, 'deleteMeasurementFile']);


// Route::post('/sitevisit/{lead}', [SiteVisitController::class, 'update']);

//hr dashboard
// Route::get('/hr/dashboard', function () {
//     return view('dashboards.hr');
// })->name('hr.dashboard');

//designer dashboard
// Route::get('/designer/dashboard', function () {
//     return view('dashboards.designer');
// })->name('designer.dashboard');

//accounts dashboard
// Route::get('/accounts/dashboard', function () {
//     return view('dashboards.accounts');
// })->name('accounts.dashboard');
