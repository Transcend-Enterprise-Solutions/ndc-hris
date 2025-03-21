<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Livewire\AuditLogViewer;
use App\Livewire\LogIndex;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

Route::redirect('/', '/login');
Route::get('/register', function () {
    return view('registeraccount'); })->name('register');


/* Super Admin account role */
Route::middleware(['auth', 'checkrole:sa'])->group(function () {
    Route::get('/configuration', function () {
        return view('livewire.admin.configuration'); })->name('configuration');
    Route::get('/audit-logs', function () {
        return view('livewire.log-index'); })->name('audit-logs');
});


/* Super Admin and HR account role */
Route::middleware(['auth', 'checkrole:sa,hr'])->group(function () {
    Route::get('/org-management', function () {
        return view('livewire.admin.role-management'); })->name('org-management');
    Route::get('/employee-management/employees', function () {
        return view('livewire.admin.employees'); })->name('/employee-management/employees');
    Route::get('/employee-management/service-records', function () {
        return view('livewire.admin.service-record'); })->name('/employee-management/service-records');
    Route::get('/employee-management/wes-management', function () {
        return view('livewire.admin.w-e-s-management'); })->name('/employee-management/wes-management');
    Route::get('/employee-management/bir2316', function () {
        return view('livewire.admin.bir2316'); })->name('/employee-management/bir2316');
    Route::get('/employee-management/admin-doc-request', function () {
        return view('livewire.admin.admin-doc-request'); })->name('/employee-management/admin-doc-request');
    Route::get('/employee-management/admin-wfh-request', function () {
        return view('livewire.admin.wfh-sched'); })->name('/employee-management/admin-wfh-request');
    Route::get('/employee-management/emp-documents', function () {
        return view('livewire.admin.emp-documents'); })->name('/employee-management/emp-documents');
    Route::get('/employee-management/admin-schedule', function () {
        return view('livewire.admin.admin-schedule'); })->name('/employee-management/admin-schedule');
    // Route::get('/employee-management/wfh-management', function () {
    //     return view('livewire.admin.wfh-management'); })->name('/employee-management/wfh-management');
    Route::get('/report-generation', function () {
        return view('livewire.admin.report-generation'); })->name('report-generation');
    Route::get('/employee-management/employee-registrations', function () {
        return view('livewire.admin.employee-registration'); })->name('/employee-management/employee-registrations');
});

/* Super Admin, HR, Supervisor, and Payroll account role */
Route::middleware(['auth', 'checkrole:sa,hr,sv,pa'])->group(function () {
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/* Super Admin, HR, and Supervisor account role */
Route::middleware(['auth', 'checkrole:sa,hr,sv'])->group(function () {
    Route::get('/employee-management/admin-dtr', function () {
        return view('livewire.admin.admin-dtr'); })->name('/employee-management/admin-dtr');
    Route::get('/leave-management/admin-leave-request', function () {
        return view('livewire.admin.admin-leave-request'); })->name('/leave-management/admin-leave-request');
    Route::get('/leave-management/admin-leave-records', function () {
        return view('livewire.admin.admin-leave-records'); })->name('/leave-management/admin-leave-records');
    Route::get('/leave-management/admin-leave-credits', function () {
        return view('livewire.admin.admin-leave-credits'); })->name('/leave-management/admin-leave-credits');
    Route::get('/leave-management/admin-leave-monetization', function () {
        return view('livewire.admin.admin-leave-monetization'); })->name('/leave-management/admin-leave-monetization');
    Route::get('/employee-management/admin-official-business', function () {
        return view('livewire.admin.admin-official-business'); })->name('/employee-management/admin-official-business');
});

/* Super Admin, HR, and Payroll account role */
Route::middleware(['auth', 'checkrole:sa,hr,pa'])->group(function () {
    // Payroll Tabs -------------------------------------------------------------------------- //
    Route::get('/payroll/plantilla-payroll', function () {
        return view('livewire.admin.general-payroll'); })->name('/payroll/plantilla-payroll');
    Route::get('/payroll/cos-payroll', function () {
        return view('livewire.admin.payroll'); })->name('/payroll/cos-payroll');
});

/* Employee account role */
Route::middleware(['auth', 'checkrole:emp'])->group(function () {
    Route::get('/home', function () {
        return view('livewire.user.home'); })->name('home');

    // My Records Tabs ------------------------------------------------------------------------ //
    Route::get('/my-records/personal-data-sheet', function () {
        return view('livewire.user.personal-data-sheet'); })->name('/my-records/personal-data-sheet');
    Route::get('/my-records/work-experience-sheet', function () {
        return view('livewire.user.work-experience-sheet'); })->name('/my-records/work-experience-sheet');
    Route::get('/my-records/my-documents', function () {
        return view('livewire.user.my-documents'); })->name('/my-records/my-documents');
    Route::get('/my-records/doc-request', function () {
        return view('livewire.user.doc-request'); })->name('/my-records/doc-request');
    Route::get('/my-records/my-virtual-id', function () {
        return view('livewire.user.my-virtual-id'); })->name('/my-records/my-virtual-id');

    // Daily Time Records Tabs ---------------------------------------------------------------- //
    Route::get('/daily-time-record/dtr', function () {
        return view('livewire.user.dtr'); })->name('/daily-time-record/dtr');
    Route::get('/daily-time-record/official-business', function () {
        return view('livewire.user.official-business'); })->name('/daily-time-record/official-business');
    Route::get('/daily-time-record/my-schedule', function () {
        return view('livewire.user.my-schedule'); })->name('/daily-time-record/my-schedule');
    Route::get('/daily-time-record/payslip', function () {
        return view('livewire.user.payslip'); })->name('/daily-time-record/payslip');
    Route::get('/daily-time-record/wfh-sched', function () {
        return view('livewire.user.wfh-sched'); })->name('/daily-time-record/wfh-sched');

    // Filing and Approval Tabs --------------------------------------------------------------- //
    Route::get('/filing-and-approval/leave-application', function () {
        return view('livewire.user.leave-application'); })->name('/filing-and-approval/leave-application');
    Route::get('/filing-and-approval/leave-credits', function () {
        return view('livewire.user.leave-credits'); })->name('/filing-and-approval/leave-credits');
    Route::get('/filing-and-approval/leave-monetization', function () {
        return view('livewire.user.leave-monetization'); })->name('/filing-and-approval/leave-monetization');
});


Route::get('/signature/{filename}', function ($filename) {
    $path = 'signatures/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = File::mimeType(storage_path('app/public/' . $path));

    return response($file, 200)->header('Content-Type', $type);
})->name('signature.file');

Route::get('/pds-photo/{filename}', function ($filename) {
    $path = 'pds-photos/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = File::mimeType(storage_path('app/public/' . $path));

    return response($file, 200)->header('Content-Type', $type);
})->name('pds-photo.file');

Route::get('/profile-photo/{filename}', function ($filename) {
    $path = 'profile-photos/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = File::mimeType(storage_path('app/public/' . $path));

    return response($file, 200)->header('Content-Type', $type);
})->name('profile-photo.file');

Route::get('/signature/{filename}', function ($filename) {
    $path = 'signature/' . $filename;

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $file = Storage::disk('public')->get($path);
    $type = File::mimeType(storage_path('app/public/' . $path));

    return response($file, 200)->header('Content-Type', $type);
})->name('signature.file');
