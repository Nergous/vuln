<?php

use App\Http\Controllers\VulnerabilitiesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/users', [AuthController::class, 'index'])->name('users.index')->middleware('auth', 'role:Admin');

Route::get('/users/create', [AuthController::class, 'create'])->name('users.create')->middleware('auth', 'role:Admin');
Route::post('/users/create', [AuthController::class, 'store'])->name('users.store')->middleware('auth', 'role:Admin');

Route::get('/users/{id}/edit', [AuthController::class, 'edit'])->name('users.edit')->middleware('auth', 'role:Admin');
Route::delete('/users/{id}', [AuthController::class, 'destroy'])->name('users.destroy')->middleware('auth', 'role:Admin');
Route::put('/users/{id}', [AuthController::class, 'update'])->name('users.update')->middleware('auth', 'role:Admin');

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::post('/report', [ReportController::class, 'store'])->name('report.store');
Route::get('/report/create', [ReportController::class, 'create'])->name('report.create');

Route::get('/report/{id}/edit', [ReportController::class, 'edit'])->name('report.edit')->middleware('auth', 'role:Admin');
Route::delete('/report/{id}', [ReportController::class, 'destroy'])->name('report.destroy')->middleware('auth', 'role:Admin');
Route::put('/report/{id}', [ReportController::class, 'update'])->name('report.update')->middleware('auth', 'role:Admin');


Route::get('/report/{id}', [ReportController::class, 'show'])->name('report.all_vulnerabilites')->middleware('auth', 'role:Admin');
Route::get('/report/change_vulnerability/{id}', [VulnerabilitiesController::class, 'changeVulnerability'])->name('report.change_vulnerability')->middleware('auth', 'role:Admin');
Route::put('/report/change_vulnerability/{id}/update', [VulnerabilitiesController::class, 'updateVulnerability'])->name('report.update_vulnerability')->middleware('auth', 'role:Admin');

Route::get('/delay/{id}', [ReportController::class, 'delay'])->name('report.delay')->middleware('auth', 'role:Admin');
Route::post('/delay/{id}/update', [ReportController::class, 'createDelay'])->name('report.create_delay')->middleware('auth', 'role:Admin');

Route::get('/export/yearly', [ReportController::class, 'exportYearly'])->name('export.yearly');

Route::get('/download/{filename}', function ($filename) {
    $path = storage_path('app/documents/' . $filename);
    if (file_exists($path)) {
        return response()->download($path);
    } else {
        abort(404);
    }
})->where('filename', '.*')->name('download.file');


Route::post('/upload-image', [VulnerabilitiesController::class, 'uploadImage'])->name('upload.image');

Route::post('/save-compensating-solution', [VulnerabilitiesController::class, 'saveCompensatingSolution'])->name('save.compensating.solution');
Route::post('/add-new-status', [VulnerabilitiesController::class, 'addNewStatus'])->name('add.new.status');
Route::get('/download-file/{id}', [VulnerabilitiesController::class, 'downloadFile'])->name('download.file');

Route::get('/report_download', [ReportController::class, 'showDownloadPage'])->name('report.download');
Route::post('/save-tag', [ReportController::class, 'saveTag'])->name('save.tag');