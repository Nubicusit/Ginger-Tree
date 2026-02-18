<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SiteVisitController;
use App\Http\Controllers\Sales\QuotationController;
use App\Http\Controllers\HRController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/hr/clear-cache', [HRController::class, 'clearCache'])->name('hr.clear-cache');
// sales executive
Route::get('/sales/dashboard', [SalesController::class, 'dashboard'])->middleware(['auth', 'department:sales_executive'])->name('sales_executive.dashboard');
Route::get('/sales/leads', [SalesController::class, 'leads'])->name('sales.leads')->middleware(['auth', 'department:sales_executive']);
Route::get('/leads/{lead}', [SalesController::class, 'showJson'])->middleware(['auth', 'department:sales_executive']);
Route::post('/tasks/store', [SalesController::class, 'store'])->name('tasks.store')->middleware(['auth', 'department:sales_executive']);
Route::post('/tasks/{id}/update', [SalesController::class, 'updateTask'])->middleware(['auth', 'department:sales_executive']);
Route::get('/leads/{lead}/tasks', [SalesController::class, 'leadTasks'])->middleware(['auth', 'department:sales_executive']);
Route::delete('/tasks/{id}', [SalesController::class, 'deleteTask'])->middleware(['auth', 'department:sales_executive']);
Route::put('/tasks/{id}', [SalesController::class, 'updateTask'])->middleware(['auth', 'department:sales_executive']);
Route::put('/leads/{lead}/update', [SalesController::class, 'update'])->middleware(['auth', 'department:sales_executive']);
Route::get('/users/create', [SalesController::class, 'create'])->name('users.create')->middleware(['auth', 'department:sales_executive']);

// customer-admin
Route::get('/customers', [CustomerController::class, 'index'])->middleware(['auth', 'admin'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');

// admin dashboard
Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');
Route::get('/leads', [AdminController::class, 'leads'])->middleware(['auth', 'admin'])->name('leads');
Route::get('/leads/create', [AdminController::class, 'create'])->middleware(['auth', 'admin'])->name('leads.create');
Route::post('/leads', [AdminController::class, 'store'])->middleware(['auth', 'admin'])->name('leads.store');
Route::get('/leads/{lead}/edit', [AdminController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [AdminController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])->name('leads.destroy');
Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);
Route::get('/inventory', [AdminController::class, 'stocks'])->name('inventory.index');

Route::get('/useraccounts', [AdminController::class, 'useraccounts'])->middleware(['auth', 'admin'])->name('useraccounts');
Route::post('/useraccounts/store', [AdminController::class, 'storeUser'])->middleware(['auth', 'admin'])->name('users.store');
Route::post('/departments/store', [AdminController::class, 'storeDepartment'])->middleware(['auth','admin'])->name('departments.store');
Route::delete('/useraccounts/{user}', [AdminController::class, 'destroyUser'])->middleware(['auth', 'admin'])->name('users.destroy');

// Route::post('/marketing/store', [MarketingController::class, 'store'])->name('marketing.store');

/*
|--------------------------------------------------------------------------
| Sales Routes
|--------------------------------------------------------------------------
*/
Route::get('/sales/sitevisits', [SiteVisitController::class, 'sitevisit'])
    ->name('sales.sitevisit')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Lead Management Routes
|--------------------------------------------------------------------------
*/
Route::post('/leads/{lead}/assign', [AdminController::class, 'assign'])->name('leads.assign');
Route::get('/lead/{id}', [AdminController::class, 'getLead']);

/*
|--------------------------------------------------------------------------
| Marketing Routes
|--------------------------------------------------------------------------
*/
Route::post('/marketing-store', [AdminController::class, 'storeMarketing'])->name('marketing.store');

/*
|--------------------------------------------------------------------------
| Site Visit Routes
|--------------------------------------------------------------------------
*/
Route::post('/sale-executive/site-visit/update', [SiteVisitController::class, 'storeOrUpdate'])->name('sitevisit.update');
Route::get('/sale-executive/site-visit/{lead}', [SiteVisitController::class, 'show'])->name('sitevisit.show');
Route::post('/sale-executive/site-visit/delete-file', [SiteVisitController::class, 'deleteMeasurementFile']);
Route::post('/leads/{lead}/site-visit', [SiteVisitController::class, 'saveSiteVisit']);

Route::get('/sale-executive/quotations/{lead}', [QuotationController::class, 'index'])->name('sale.quotations.index');

Route::post('quotations/store', [QuotationController::class, 'store'])->name('sale.quotations.store');

/*
|--------------------------------------------------------------------------
| HR Routes
|--------------------------------------------------------------------------
*/
Route::get('/hr/dashboard', [HRController::class, 'dashboard'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.dashboard');

Route::get('/hr/employees', [HRController::class, 'employees'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.employees');

// hr dashboard
// Route::get('/hr/dashboard', function () {
//     return view('dashboards.hr');
// })->name('hr.dashboard');

// designer dashboard
// Route::get('/designer/dashboard', function () {
//     return view('dashboards.designer');
// })->name('designer.dashboard');

// accounts dashboard
// Route::get('/accounts/dashboard', function () {
//     return view('dashboards.accounts');
// })->name('accounts.dashboard');

Route::post('/hr/employees', [HRController::class, 'storeEmployee'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.employees.store');

Route::get('/hr/attendance', [HRController::class, 'attendance'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance');

Route::get('/hr/salary/create', [HRController::class, 'createSalary'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.salary.create');

Route::get('/hr/payroll', [HRController::class, 'payroll'])
   ->middleware(['auth', 'department:hr'])
    ->name('hr.payroll');

// **NEW: Self Check-in Detail Page (for HR Dashboard link)**
Route::get('/hr/self-checkin', [HRController::class, 'selfCheckinReport'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.self.checkin');

// HR Attendance CRUD
Route::post('/hr/attendance', [HRController::class, 'store'])
   ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.store');

Route::get('/hr/attendance/{id}/edit', [HRController::class, 'edit'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.edit');

Route::put('/hr/attendance/{id}', [HRController::class, 'update'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.update');

Route::delete('/hr/attendance/{id}', [HRController::class, 'destroy'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.destroy');

// HR Quick Actions
Route::post('/hr/attendance/check-in', [HRController::class, 'checkIn'])
   ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.checkin');

Route::post('/hr/attendance/check-out', [HRController::class, 'checkOut'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.checkout');

// Reports
Route::get('/hr/attendance/report', [HRController::class, 'report'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.report');

/*
|--------------------------------------------------------------------------
| Employee Self Check-in/Check-out (All authenticated users)
|--------------------------------------------------------------------------
*/
Route::post('/attendance/self-check-in', [HRController::class, 'selfCheckIn'])
    ->middleware(['auth'])
    ->name('attendance.self-check-in');

Route::post('/attendance/self-check-out', [HRController::class, 'selfCheckOut'])
    ->middleware(['auth'])
    ->name('attendance.self-check-out');

/*
|--------------------------------------------------------------------------
| HR Modal API Routes (NO GROUP!)
|--------------------------------------------------------------------------
*/
// Modal API routes - INDIVIDUAL routes only
Route::get('/hr/attendance/status', [HRController::class, 'attendanceStatus'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.attendance.status');

Route::post('/hr/self-checkin', [HRController::class, 'selfCheckIn'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.self.checkin');

Route::get('/hr/today-attendance', [HRController::class, 'todayAttendance'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.today.attendance');
