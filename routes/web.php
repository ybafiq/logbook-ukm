<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\STBC4866EntryController;
use App\Http\Controllers\STBC4966EntryController;
use App\Http\Controllers\STBC4886EntryController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\MergePdfController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home/daily-counts', [HomeController::class, 'dailyCounts'])->name('home.dailyCounts')->middleware('auth');

Route::get('STBC4866', [STBC4866EntryController::class, 'index'])->name('STBC4866.index');
Route::get('STBC4866/create', [STBC4866EntryController::class, 'create'])->name('STBC4866.create');
Route::post('STBC4866', [STBC4866EntryController::class, 'store'])->name('STBC4866.store');
Route::get('STBC4866/{stbc4866Entry}', [STBC4866EntryController::class, 'show'])->name('STBC4866.show');
Route::get('STBC4866/{stbc4866Entry}/edit', [STBC4866EntryController::class, 'edit'])->name('STBC4866.edit');
Route::post('STBC4866/{stbc4866Entry}/edit', [STBC4866EntryController::class, 'update'])->name('STBC4866.update');
Route::get('STBC4866/{stbc4866Entry}/delete', [STBC4866EntryController::class, 'delete'])->name('STBC4866.delete');
Route::delete('STBC4866/{stbc4866Entry}', [STBC4866EntryController::class, 'destroy'])->name('STBC4866.destroy');

Route::get('STBC4966', [STBC4966EntryController::class, 'index'])->name('STBC4966.index');
Route::get('STBC4966/create', [STBC4966EntryController::class, 'create'])->name('STBC4966.create');
Route::post('STBC4966', [STBC4966EntryController::class, 'store'])->name('STBC4966.store');
Route::get('STBC4966/{stbc4966Entry}', [STBC4966EntryController::class, 'show'])->name('STBC4966.show');
Route::get('STBC4966/{stbc4966Entry}/edit', [STBC4966EntryController::class, 'edit'])->name('STBC4966.edit');
Route::post('STBC4966/{stbc4966Entry}/edit', [STBC4966EntryController::class, 'update'])->name('STBC4966.update');
Route::get('STBC4966/{stbc4966Entry}/delete', [STBC4966EntryController::class, 'delete'])->name('STBC4966.delete');
Route::delete('STBC4966/{stbc4966Entry}', [STBC4966EntryController::class, 'destroy'])->name('STBC4966.destroy');

Route::get('STBC4886', [STBC4886EntryController::class, 'index'])->name('STBC4886.index');
Route::get('STBC4886/create', [STBC4886EntryController::class, 'create'])->name('STBC4886.create');
Route::post('STBC4886', [STBC4886EntryController::class, 'store'])->name('STBC4886.store');
Route::get('STBC4886/{stbc4886Entry}', [STBC4886EntryController::class, 'show'])->name('STBC4886.show');
Route::get('STBC4886/{stbc4886Entry}/edit', [STBC4886EntryController::class, 'edit'])->name('STBC4886.edit');
Route::post('STBC4886/{stbc4886Entry}/edit', [STBC4886EntryController::class, 'update'])->name('STBC4886.update');
Route::get('STBC4886/{stbc4886Entry}/delete', [STBC4886EntryController::class, 'delete'])->name('STBC4886.delete');
Route::delete('STBC4886/{stbc4886Entry}', [STBC4886EntryController::class, 'destroy'])->name('STBC4886.destroy');

// User routes with role-based access
Route::middleware('auth')->group(function () {
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.updateProfile');
    
    // Student-only PDF export routes
    Route::middleware('role:student')->group(function () {
        Route::get('export', [App\Http\Controllers\UserController::class, 'showExportForm'])->name('users.showExport');
        Route::get('export/logbook', [App\Http\Controllers\UserController::class, 'exportLogbook'])->name('users.exportLogbook');
    });
    
    // Admin-only user management routes - SPECIFIC ROUTES FIRST
    Route::get('users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create')->middleware('role:admin');
    Route::get('users/trashed', [App\Http\Controllers\UserController::class, 'trashed'])->name('users.trashed')->middleware('role:admin');
    Route::post('users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware('role:admin');
    Route::post('users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore')->middleware('role:admin');
    Route::delete('users/{id}/force', [App\Http\Controllers\UserController::class, 'forceDestroy'])->name('users.force-destroy')->middleware('role:admin');
    Route::get('users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit')->middleware('role:admin');
    Route::put('users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update')->middleware('role:admin');
    Route::delete('users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy')->middleware('role:admin');
    
    // General user routes - PARAMETERIZED ROUTES LAST
    Route::get('users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
});



// Supervisor routes
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    Route::get('supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::get('supervisor/pending-entries', [SupervisorController::class, 'pendingEntries'])->name('supervisor.pendingEntries');
    Route::get('supervisor/pending-project-entries', [SupervisorController::class, 'pendingProjectEntries'])->name('supervisor.pendingProjectEntries');
    Route::get('supervisor/pending-stbc4886-entries', [SupervisorController::class, 'pendingStbc4886Entries'])->name('supervisor.pendingStbc4886Entries');
    Route::post('supervisor/approve-entry/{entry}', [SupervisorController::class, 'approveEntry'])->name('supervisor.approveEntry');
    Route::post('supervisor/approve-project-entry/{stbc4966Entry}', [SupervisorController::class, 'approveProjectEntry'])->name('supervisor.approveProjectEntry');
    Route::post('supervisor/approve-stbc4886-entry/{stbc4886Entry}', [SupervisorController::class, 'approveStbc4886Entry'])->name('supervisor.approveStbc4886Entry');
    Route::post('supervisor/mark-all-read', [SupervisorController::class, 'markAllRead'])->name('supervisor.markAllRead');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('admin/signatures', [App\Http\Controllers\AdminController::class, 'manageSignatures'])->name('admin.manageSignatures');
    Route::delete('admin/signatures/log/{entry}', [App\Http\Controllers\AdminController::class, 'deleteLogSignature'])->name('admin.deleteLogSignature');
    Route::delete('admin/signatures/project/{entry}', [App\Http\Controllers\AdminController::class, 'deleteProjectSignature'])->name('admin.deleteProjectSignature');
});


Route::get('students/{student}/export', [STBC4866EntryController::class, 'exportLogbook'])->name('students.export');

Route::get('/pdf/merge', [MergePdfController::class, 'showForm'])->name('pdf.merge.form');
Route::get('/pdf/merge', function() {
    return view('pdf.merge-form'); // adjust to your actual view
})->name('pdf.merge.form');

Route::post('/pdf/merge', [MergePdfController::class, 'merge'])->name('pdf.merge');
Route::post('/pdf/merge/preview', [MergePdfController::class, 'preview'])->name('pdf.merge.preview');



