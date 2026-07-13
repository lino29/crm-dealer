<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ============ ADMIN ROUTES ============
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'adminIndex'])->name('dashboard');

        // K7: User Management (AUTH-04)
        Route::resource('/users', \App\Http\Controllers\UserController::class)->except(['show', 'destroy']);

        // Dealer CRUD
        Route::resource('/dealers', \App\Http\Controllers\DealerController::class);

        // Bulk Customer Export (must be before resource route to avoid {customer} wildcard conflict)
        Route::post('/customers/bulk-export/excel', [\App\Http\Controllers\CustomerController::class, 'bulkExportExcel'])->name('customers.bulk_export_excel');
        Route::post('/customers/bulk-export/pdf', [\App\Http\Controllers\CustomerController::class, 'bulkExportPdf'])->name('customers.bulk_export_pdf');

        // Bulk Member Card Print (must be before resource route to avoid {customer} wildcard conflict)
        Route::post('/customers/member-cards/bulk-print', [\App\Http\Controllers\MemberCardController::class, 'bulkPrint'])->name('member_cards.bulk_print');
        Route::post('/customers/member-cards/bulk-print-a4', [\App\Http\Controllers\MemberCardController::class, 'bulkPrintA4'])->name('member_cards.bulk_print_a4');

        // Customer CRUD
        Route::resource('/customers', \App\Http\Controllers\CustomerController::class);

        // Member Card
        Route::post('/customers/{customer}/member-card/generate', [\App\Http\Controllers\MemberCardController::class, 'generate'])->name('member_cards.generate');
        Route::get('/customers/{customer}/member-card/preview', [\App\Http\Controllers\MemberCardController::class, 'preview'])->name('member_cards.preview');
        Route::get('/customers/{customer}/member-card/print', [\App\Http\Controllers\MemberCardController::class, 'print'])->name('member_cards.print');
        Route::post('/customers/{customer}/member-card/regenerate', [\App\Http\Controllers\MemberCardController::class, 'regenerate'])->name('member_cards.regenerate');

        
        // Vehicle CRUD
        Route::resource('/vehicles', \App\Http\Controllers\VehicleController::class);

        // Service History
        Route::get('/vehicles/{vehicle}/service_histories', [\App\Http\Controllers\ServiceHistoryController::class, 'index'])->name('vehicles.service_histories.index');
        Route::get('/vehicles/{vehicle}/service_histories/create', [\App\Http\Controllers\ServiceHistoryController::class, 'create'])->name('vehicles.service_histories.create');
        Route::post('/vehicles/service_histories', [\App\Http\Controllers\ServiceHistoryController::class, 'store'])->name('vehicles.service_histories.store');
        Route::get('/service_histories/{history}/edit', [\App\Http\Controllers\ServiceHistoryController::class, 'edit'])->name('service_histories.edit');
        Route::put('/service_histories/{history}', [\App\Http\Controllers\ServiceHistoryController::class, 'update'])->name('service_histories.update');
        
        // Scan
        Route::get('/scan', [\App\Http\Controllers\ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan/validate', [\App\Http\Controllers\ScanController::class, 'validateToken'])->name('scan.validate');

        // Service Schedules
        Route::get('/service-schedules', [\App\Http\Controllers\ServiceScheduleController::class, 'index'])->name('service_schedules.index');
        Route::patch('/service-schedules/{schedule}/complete', [\App\Http\Controllers\ServiceScheduleController::class, 'complete'])->name('service_schedules.complete');
        Route::patch('/service-schedules/{schedule}/cancel', [\App\Http\Controllers\ServiceScheduleController::class, 'cancel'])->name('service_schedules.cancel');

        // S2: WhatsApp Reminder
        Route::get('/whatsapp-reminders', [\App\Http\Controllers\WhatsAppReminderController::class, 'index'])->name('whatsapp.index');
        Route::post('/whatsapp-reminders/send', [\App\Http\Controllers\WhatsAppReminderController::class, 'send'])->name('whatsapp.send');
        Route::post('/whatsapp-reminders/{notification}/retry', [\App\Http\Controllers\WhatsAppReminderController::class, 'retry'])->name('whatsapp.retry');

        // Reports (admin can also view)
        Route::get('/reports/customers', [\App\Http\Controllers\ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/services', [\App\Http\Controllers\ReportController::class, 'services'])->name('reports.services');

        // Audit
        Route::get('/audit/scan-logs', [\App\Http\Controllers\AuditController::class, 'scanLogs'])->name('audit.scan_logs');
        Route::get('/audit/notification-logs', [\App\Http\Controllers\AuditController::class, 'notificationLogs'])->name('audit.notification_logs');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });

// ============ LEADER ROUTES (K6) ============
Route::middleware(['auth', 'role:leader'])
    ->prefix('leader')
    ->name('leader.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'leaderIndex'])->name('dashboard');

        // Reports
        Route::get('/reports/customers', [\App\Http\Controllers\ReportController::class, 'customers'])->name('reports.customers');
        Route::get('/reports/vehicles', [\App\Http\Controllers\ReportController::class, 'vehicles'])->name('reports.vehicles');
        Route::get('/reports/service-histories', [\App\Http\Controllers\ReportController::class, 'serviceHistories'])->name('reports.service-histories');
        Route::get('/reports/service-schedules', [\App\Http\Controllers\ReportController::class, 'serviceSchedules'])->name('reports.service-schedules');
        Route::get('/reports/whatsapp-notifications', [\App\Http\Controllers\ReportController::class, 'whatsappNotifications'])->name('reports.whatsapp-notifications');
        Route::get('/reports/scan-logs', [\App\Http\Controllers\ReportController::class, 'scanLogs'])->name('reports.scan-logs');

        // Export
        Route::get('/reports/{type}/export/pdf', [\App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::get('/reports/{type}/export/excel', [\App\Http\Controllers\ReportController::class, 'exportExcel'])->name('reports.export.excel');
    });

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
