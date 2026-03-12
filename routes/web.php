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
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\EstimationController;

Route::get('/', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/hr/clear-cache', [HRController::class, 'clearCache'])->name('hr.clear-cache');Route::post('/hr/clear-cache', [HRController::class, 'clearCache'])->name('hr.clear-cache');
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


Route::get('/sale-executive/estimators', [SalesController::class, 'getEstimators']);
// customer-admin
Route::get('/customers', [CustomerController::class, 'index'])->middleware(['auth', 'admin'])->name('customers.index');
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');

// admin-dashboard
Route::get('/dashboard', [AdminController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');
Route::get('/leads', [AdminController::class, 'leads'])->middleware(['auth', 'admin'])->name('leads');
Route::get('/leads/create', [AdminController::class, 'create'])->middleware(['auth', 'admin'])->name('leads.create');
Route::post('/leads', [AdminController::class, 'store'])->middleware(['auth', 'admin'])->name('leads.store');
Route::get('/leads/{lead}/edit', [AdminController::class, 'edit'])->name('leads.edit');
Route::put('/leads/{lead}', [AdminController::class, 'update'])->name('leads.update');
Route::delete('/leads/{lead}', [AdminController::class, 'destroy'])->name('leads.destroy');
Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus']);
Route::get('/inventory', [AdminController::class, 'stocks'])->name('inventory.index');
Route::get('inventory/create', [AdminController::class, 'createstock'])->name('inventory.create');
Route::post('inventory/store', [AdminController::class, 'storestock'])->name('inventory.store');
Route::get('inventory/{id}/edit', [AdminController::class, 'editstock'])->name('inventory.edit');
Route::put('inventory/{id}', [AdminController::class, 'updatestock'])->name('inventory.update');
Route::delete('inventory/{id}', [AdminController::class, 'destroystock'])->name('inventory.destroy');
Route::get('/inventory/items', [AdminController::class, 'getInventoryItems'])->name('inventory.items');
// Route::post('/customer/create-credentials', [CustomerController::class, 'createCredentials'])
//     ->name('customer.create.credentials');

Route::get('/services', [AdminController::class, 'services'])->name('services.index');
Route::get('services/create', [AdminController::class, 'createservice'])->name('services.create');
Route::post('services/store', [AdminController::class, 'storeservice'])->name('services.store');
Route::get('services/{id}/edit', [AdminController::class, 'editservice'])->name('services.edit');
Route::put('services/{id}', [AdminController::class, 'updateservice'])->name('services.update');
Route::delete('services/{id}', [AdminController::class, 'destroyservice'])->name('services.destroy');
Route::get('/inventory/items', [AdminController::class, 'getInventoryItems'])->name('inventory.items');

Route::get('/useraccounts', [AdminController::class, 'useraccounts'])->middleware(['auth', 'admin'])->name('useraccounts');
Route::post('/useraccounts/store', [AdminController::class, 'storeUser'])->middleware(['auth', 'admin'])->name('users.store');
Route::post('/departments/store', [AdminController::class, 'storeDepartment'])->middleware(['auth','admin'])->name('departments.store');
Route::delete('/useraccounts/{user}', [AdminController::class, 'destroyUser'])->middleware(['auth', 'admin'])->name('users.destroy');

// csv
Route::post('/leads/import', [AdminController::class, 'import'])->name('leads.import');
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
Route::post('/marketing-store', [AdminController::class, 'storeMarketing'])->name('admin.marketing.store');

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

Route::post('/sale-executive/quotation/store', [QuotationController::class, 'storequotation'])->name('sale.quotations.store');
Route::post('/quotation/{id}/update-status', [QuotationController::class, 'updateStatus']);
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
    Route::put('/hr/employees/{id}',    [HRController::class, 'updateEmployee'])->name('hr.employees.update');
Route::delete('/hr/employees/{id}', [HRController::class, 'destroyEmployee'])->name('hr.employees.destroy');
    Route::put('/hr/employees/{id}',    [HRController::class, 'updateEmployee'])->name('hr.employees.update');
Route::delete('/hr/employees/{id}', [HRController::class, 'destroyEmployee'])->name('hr.employees.destroy');

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
Route::post('/self-checkout', [HRController::class, 'checkout'])->name('hr.self.checkout');
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
    Route::post('/self-checkout', [HrController::class, 'selfCheckout'])->name('hr.self.checkout');
    Route::post('/self-checkout', [HrController::class, 'selfCheckout'])->name('hr.self.checkout');

Route::get('/hr/today-attendance', [HRController::class, 'todayAttendance'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.today.attendance');

Route::post('sales/attendance/self-check-in', [SalesController::class, 'selfCheckIn'])
    ->middleware(['auth','department:sales_executive'])
    ->name('sales.attendance.self-check-in');

Route::post('sales/attendance/self-check-out', [SalesController::class, 'selfCheckOut'])
    ->middleware(['auth','department:sales_executive'])
    ->name('sales.attendance.self-check-out');
Route::get('/sales/attendance/status', [SalesController::class, 'attendanceStatus'])
    ->middleware(['auth', 'department:sales_executive'])
    ->name('sales.attendance.status');

// Route::get('/sales/self-checkin', [SalesController::class, 'selfCheckinReport'])
//     ->middleware(['auth', 'department:sales_executive'])
//     ->name('sales.self.checkin');

Route::get('/quotation/generate-number', [QuotationController::class, 'generateNumber'])->name('quotation.generate-number');
Route::get('/inventory/items', [QuotationController::class, 'getItems']);
Route::post('sales/attendance/self-check-in', [SalesController::class, 'selfCheckIn'])
    ->middleware(['auth','department:sales_executive'])
    ->name('sales.attendance.self-check-in');

Route::post('sales/attendance/self-check-out', [SalesController::class, 'selfCheckOut'])
    ->middleware(['auth','department:sales_executive'])
    ->name('sales.attendance.self-check-out');
Route::get('/sales/attendance/status', [SalesController::class, 'attendanceStatus'])
    ->middleware(['auth', 'department:sales_executive'])
    ->name('sales.attendance.status');

Route::post('/sales/leave/store', [SalesController::class, 'salesLeave'])->middleware(['auth', 'department:sales_executive'])
        ->name('sales.leave.store');


Route::get('/sales/projects', [SalesController::class, 'salesProject'])->middleware(['auth', 'department:sales_executive'])
        ->name('sales.projects');
Route::get('/sales/projects/{id}', [SalesController::class, 'showProject'])
    ->name('sales.projects.show');
Route::put('/sales/projects/{id}', [SalesController::class, 'updateProject'])
    ->name('sales.projects.update');
Route::get('/inventory-items', function () {
    return \App\Models\InventoryStock::select('id','item_name','price')->get();
});


//accounts
Route::get('/accounts/dashboard', [AccountsController::class, 'dashboard'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.dashboard');
Route::get('/accounts/income-expenses', [AccountsController::class, 'incomeExpenses'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.income-expenses');
    Route::post('/accounts/transactions', [AccountsController::class, 'store'])->name('accounts.transactions.store');
Route::put('/accounts/transactions/{id}', [AccountsController::class, 'update']);
Route::delete('/accounts/transactions/{id}', [AccountsController::class, 'destroy']);


Route::get('/accounts/payroll', [AccountsController::class, 'payroll'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.payroll');

    // Invoice Routes
Route::get('/accounts/invoices', [AccountsController::class, 'invoicesIndex'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.index');

Route::get('/accounts/invoices/create', [AccountsController::class, 'invoicesCreate'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.create');

Route::post('/accounts/invoices', [AccountsController::class, 'invoicesStore'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.store');

Route::get('/accounts/invoices/{id}', [AccountsController::class, 'invoicesShow'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.show');

Route::get('/accounts/invoices/{id}/edit', [AccountsController::class, 'invoicesEdit'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.edit');

Route::put('/accounts/invoices/{id}', [AccountsController::class, 'invoicesUpdate'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.update');

Route::delete('/accounts/invoices/{id}', [AccountsController::class, 'invoicesDestroy'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.destroy');

Route::get('/accounts/invoices/{id}/print', [AccountsController::class, 'invoicesPrint'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.print');

Route::patch('/accounts/invoices/{id}/approve', [AccountsController::class, 'invoicesApprove'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.approve');

Route::patch('/accounts/invoices/{id}/reject', [AccountsController::class, 'invoicesReject'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.reject');

Route::patch('/accounts/invoices/{id}/negotiate', [AccountsController::class, 'invoicesNegotiate'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.invoices.negotiate');

Route::post('/hr/payroll/ot/update', [HRController::class, 'updateOt'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.payroll.ot.update');
Route::post('/hr/payroll/advance/store', [HRController::class, 'storeAdvance'])
    ->middleware(['auth', 'department:hr'])
    ->name('hr.payroll.advance.store');

Route::get('/hr/leaves', [HRController::class, 'leaves'])->name('hr.leaves')->middleware(['auth', 'department:hr']);
Route::post('/hr/leaves', [HRController::class, 'storeLeave'])->name('hr.leaves.store')->middleware(['auth', 'department:hr']);
Route::patch('/hr/leaves/{leave}/status', [HRController::class, 'updateLeaveStatus'])->name('hr.leaves.status')->middleware(['auth', 'department:hr']);

Route::get('/designer/dashboard', [DesignerController::class, 'dashboard'])
    ->middleware(['auth', 'designer'])
    ->name('designer.dashboard');

Route::get('/design-status', [DesignerController::class, 'index'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.index');

Route::get('/design-status/create', [DesignerController::class, 'create'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.create');

Route::post('/design-status', [DesignerController::class, 'store'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.store');

Route::get('/design-status/{id}/edit', [DesignerController::class, 'edit'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.edit');

Route::put('/design-status/{id}', [DesignerController::class, 'update'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.update');

Route::delete('/design-status/{id}', [DesignerController::class, 'destroy'])
    ->middleware(['auth', 'designer'])
    ->name('design-status.destroy');

Route::get('/estimator/dashboard', [EstimationController::class,'dashboard'])->middleware(['auth', 'estimator'])->name('estimator.dashboard');
Route::get('/estimations', [EstimationController::class, 'estimation'])->middleware(['auth', 'estimator'])->name('estimations.index');
Route::get('/estimator/quotation/create/{lead}', [EstimationController::class, 'createQuotation'])->name('estimator.quotation.create');
Route::post('/estimator/quotation/store', [EstimationController::class, 'storeQuotation'])->middleware(['auth', 'estimator'])->name('estimator.quotation.store');
// Inside your estimator auth group
Route::get('/estimator/estimation/{id}/pdf', [EstimationController::class, 'estimationPdf'])
    ->middleware(['auth', 'estimator'])
    ->name('estimator.estimation.pdf');
// Route::get('/estimator/quotation/{quotation}/pdf', [EstimationController::class, 'generatePdf'])->middleware(['auth', 'estimator'])->name('estimator.quotation.pdf');
Route::get('/estimator/estimations', [EstimationController::class, 'estimation'])->middleware(['auth', 'estimator'])->name('estimator.estimation');
Route::get('/estimator/item-details', [EstimationController::class, 'getItemDetails'])
    ->middleware(['auth','estimator'])
    ->name('estimator.item.details');

    Route::get('/accounts/estimations', [AccountsController::class, 'estimations'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations');

Route::post('/accounts/estimations', [AccountsController::class, 'estimationStore'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.store');

Route::put('/accounts/estimations/{id}', [AccountsController::class, 'estimationUpdate'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.update');

Route::delete('/accounts/estimations/{id}', [AccountsController::class, 'estimationDestroy'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.destroy');

Route::get('/accounts/estimations/{id}/edit-data', [AccountsController::class, 'estimationEditData'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.edit-data');

Route::get('/accounts/estimations/{id}/view-data', [AccountsController::class, 'estimationViewData'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.view-data');

Route::get('/accounts/estimations/{id}/pdf', [AccountsController::class, 'estimationPdf'])
    ->middleware(['auth', 'department:accounts'])
    ->name('accounts.estimations.pdf');
