<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SalaryComponentController;
use App\Http\Controllers\DeductionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeShiftController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ShiftTemplateController;
use App\Http\Controllers\EmployeeSettingController;
use App\Http\Controllers\EmployeeController;


Route::get('/', function () {
    return redirect()->route('login');
});
Route::middleware(['auth'])->group(function () {
    // Route Dashboard Tunggal (Menggunakan Controller)

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/presence', [PresenceController::class, 'index'])->name('presence.index');
    Route::get('/my-payrolls', [PresenceController::class, 'myPayrolls'])->name('presence.payrolls');
    Route::get('/presence/live-gps', [PresenceController::class, 'liveGps'])->name('presence.live_gps');
    Route::post('/presence/store', [PresenceController::class, 'store'])->name('presence.store');

    // Route Izin cuti
    Route::get('/my-leaves', [LeaveController::class, 'employeeIndex'])->name('employee.leaves.index');
    Route::post('/my-leaves', [LeaveController::class, 'employeeStore'])->name('employee.leaves.store');
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::post('/leaves/{id}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::delete('/leaves/{id}', [LeaveController::class, 'destroy'])->name('leaves.destroy');

    // Route Pengaturan Akun Karyawan
    Route::get('/employee/settings', [EmployeeSettingController::class, 'index'])->name('employee.settings.index');
    Route::put('/employee/settings/phone', [EmployeeSettingController::class, 'updatePhone'])->name('employee.settings.updatePhone');
    Route::put('/employee/settings/password', [EmployeeSettingController::class, 'updatePassword'])->name('employee.settings.updatePassword');

    // Route Master Data
    Route::resource('locations', LocationController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('positions', PositionController::class);
    Route::resource('salary-components', SalaryComponentController::class);
    Route::resource('deductions', DeductionController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('employee-shifts', EmployeeShiftController::class);
    Route::resource('bonuses', BonusController::class);
    Route::resource('shift-templates', ShiftTemplateController::class);
    Route::resource('payrolls', PayrollController::class);

    // Custom Payroll Routes
    Route::get('payrolls-approval', [PayrollController::class, 'approval'])->name('payrolls.approval');
    Route::patch('payrolls/{payroll}/approve', [PayrollController::class, 'approve'])->name('payrolls.approve');
    Route::patch('payrolls/{payroll}/reject', [PayrollController::class, 'reject'])->name('payrolls.reject');
    Route::get('payrolls-slip', [PayrollController::class, 'slip'])->name('payrolls.slip');
    Route::get('payrolls/{payroll}/print', [PayrollController::class, 'printSlip'])->name('payrolls.print');

    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('payroll', [ReportController::class, 'payroll'])->name('payroll');
        Route::get('tax', [ReportController::class, 'tax'])->name('tax');
        Route::get('bpjs', [ReportController::class, 'bpjs'])->name('bpjs');
    });

    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings/profile', [SettingController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [SettingController::class, 'updatePassword'])->name('settings.updatePassword');
});

Route::get('/run-migrate', function () {
    try {
        Artisan::call('migrate --force');
        Artisan::call('db:seed --force');

        return response('<h1>✅ Database Migration & Seed Berhasil!</h1>');
    } catch (\Exception $e) {
        return response('<h1>❌ Error:</h1> <pre>' . $e->getMessage() . '</pre>', 500);
    }
})->withoutMiddleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
]);

require __DIR__ . '/auth.php';
