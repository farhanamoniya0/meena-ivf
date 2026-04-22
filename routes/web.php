<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\LabReportController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Home / Landing
Route::get('/', fn() => view('welcome'))->name('home');

// Auth
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::get('/patients/search',  [PatientController::class, 'search'])->name('patients.search');
    Route::get('/patients/quick',   [PatientController::class, 'quickCreate'])->name('patients.quick');
    Route::post('/patients/quick',  [PatientController::class, 'quickStore'])->name('patients.quick.store');
    Route::resource('patients', PatientController::class)->except(['destroy']);

    // Consultants
    Route::resource('consultants', ConsultantController::class)->except(['show','destroy']);

    // IVF Packages
    Route::get('/packages/assign',       [PackageController::class, 'assign'])->name('packages.assign');
    Route::post('/packages/assign',      [PackageController::class, 'assignStore'])->name('packages.assign.store');
    Route::resource('packages', PackageController::class)->except(['show','destroy']);

    // Billing
    Route::get('/billing',                      [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/today',                [BillingController::class, 'todayPayments'])->name('billing.today');
    Route::get('/billing/patient/{patient}',    [BillingController::class, 'patient'])->name('billing.patient');
    Route::get('/billing/pay/{package}',        [BillingController::class, 'payForm'])->name('billing.pay');
    Route::post('/billing/pay/{package}',       [BillingController::class, 'payStore'])->name('billing.pay.store');
    Route::get('/billing/receipt/{payment}',    [BillingController::class, 'receipt'])->name('billing.receipt');

    // Accounts
    Route::middleware('role:admin,accountant')->group(function () {
        Route::get('/accounts',              [AccountsController::class, 'dashboard'])->name('accounts.dashboard');
        Route::post('/accounts/close-day',   [AccountsController::class, 'closeDay'])->name('accounts.close');
        Route::get('/accounts/history',      [AccountsController::class, 'history'])->name('accounts.history');
        Route::post('/accounts/approve/{payment}', [AccountsController::class, 'approvePayment'])->name('accounts.approve');
        Route::post('/accounts/reject/{payment}',  [AccountsController::class, 'rejectPayment'])->name('accounts.reject');
    });

    // Pharmacy
    Route::prefix('pharmacy')->name('pharmacy.')->group(function () {
        Route::get('/',                           [PharmacyController::class, 'dashboard'])->name('dashboard');
        Route::get('/medicines',                  [PharmacyController::class, 'medicines'])->name('medicines');
        Route::get('/medicines/create',           [PharmacyController::class, 'createMedicine'])->name('medicines.create');
        Route::post('/medicines',                 [PharmacyController::class, 'storeMedicine'])->name('medicines.store');
        Route::get('/medicines/{medicine}/batches',     [PharmacyController::class, 'batches'])->name('batches');
        Route::get('/medicines/{medicine}/batches/add', [PharmacyController::class, 'addBatch'])->name('batches.add');
        Route::post('/medicines/{medicine}/batches',    [PharmacyController::class, 'storeBatch'])->name('batches.store');
        Route::get('/medicines/{medicine}/batch-list',  [PharmacyController::class, 'getBatches'])->name('batches.list');
        Route::get('/assign',                     [PharmacyController::class, 'assignForm'])->name('assign');
        Route::post('/assign',                    [PharmacyController::class, 'assignStore'])->name('assign.store');
        Route::get('/requisitions',               [PharmacyController::class, 'requisitions'])->name('requisitions');
        Route::post('/requisitions',              [PharmacyController::class, 'requisitionStore'])->name('requisitions.store');
        Route::post('/requisitions/{req}/approve',[PharmacyController::class, 'approveRequisition'])->name('requisitions.approve');
        Route::post('/requisitions/{req}/reject', [PharmacyController::class, 'rejectRequisition'])->name('requisitions.reject');
    });

    // Bills
    Route::get('/bills',                       [BillController::class, 'index'])->name('bills.index');
    Route::get('/bills/create',                [BillController::class, 'create'])->name('bills.create');
    Route::post('/bills',                      [BillController::class, 'store'])->name('bills.store');
    Route::get('/bills/{bill}',                [BillController::class, 'show'])->name('bills.show');
    Route::post('/bills/{bill}/pay',           [BillController::class, 'pay'])->name('bills.pay');
    Route::post('/patients/{patient}/credit-advance', [BillController::class, 'creditAdvance'])->name('patients.credit-advance');
    Route::get('/api/consultant/{consultant}/fee', [BillController::class, 'consultantFee'])->name('api.consultant.fee');
    Route::get('/api/services',                [ServiceController::class, 'list'])->name('api.services');

    // Service Master
    Route::resource('services', ServiceController::class)->except(['show','destroy']);
    Route::post('/services/{service}/deactivate', [ServiceController::class, 'destroy'])->name('services.deactivate');

    // Staff Master
    Route::resource('staff', StaffController::class)->except(['show','destroy']);

    // Appointments
    Route::resource('appointments', AppointmentController::class)->only(['index','create','store']);
    Route::post('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Tasks
    Route::resource('tasks', TaskController::class)->only(['index','create','store']);
    Route::post('/tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');

    // Lab Reports
    Route::prefix('lab')->name('lab.')->group(function () {
        Route::get('/reports',                      [LabReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/ready',                [LabReportController::class, 'readyList'])->name('reports.ready');
        Route::get('/reports/create',               [LabReportController::class, 'create'])->name('reports.create');
        Route::post('/reports',                     [LabReportController::class, 'store'])->name('reports.store');
        Route::get('/reports/{report}',             [LabReportController::class, 'show'])->name('reports.show');
        Route::get('/reports/{report}/edit',        [LabReportController::class, 'edit'])->name('reports.edit');
        Route::put('/reports/{report}',             [LabReportController::class, 'update'])->name('reports.update');
        Route::post('/reports/{report}/advance',    [LabReportController::class, 'advance'])->name('reports.advance');
        Route::get('/reports/{report}/print',       [LabReportController::class, 'print'])->name('reports.print');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/',                           [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users',                      [AdminController::class, 'users'])->name('users');
        Route::get('/users/create',               [AdminController::class, 'createUser'])->name('users.create');
        Route::post('/users',                     [AdminController::class, 'storeUser'])->name('users.store');
        Route::post('/users/{user}/toggle',       [AdminController::class, 'toggleUser'])->name('users.toggle');
        Route::get('/departments',                [AdminController::class, 'departments'])->name('departments');
        Route::post('/departments',               [AdminController::class, 'storeDepartment'])->name('departments.store');
        Route::post('/approve/payment/{payment}', [AdminController::class, 'approvePayment'])->name('approve.payment');
        Route::post('/reject/payment/{payment}',  [AdminController::class, 'rejectPayment'])->name('reject.payment');
        Route::post('/approve/req/{requisition}', [AdminController::class, 'approveRequisition'])->name('approve.req');
        Route::post('/reject/req/{requisition}',  [AdminController::class, 'rejectRequisition'])->name('reject.req');
    });
});
