<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogEntryController;
use App\Http\Controllers\WeeklyReflectionController;
use App\Http\Controllers\SupervisorController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('log-entries', [LogEntryController::class, 'index'])->name('log-entries.index');
Route::get('log-entries/create', [LogEntryController::class, 'create'])->name('log-entries.create');
Route::post('log-entries', [LogEntryController::class, 'store'])->name('log-entries.store');
Route::get('log-entries/{log_entry}', [LogEntryController::class, 'show'])->name('log-entries.show');
Route::get('log-entries/{log_entry}/edit', [LogEntryController::class, 'edit'])->name('log-entries.edit');
Route::post('log-entries/{log_entry}/edit', [LogEntryController::class, 'update'])->name('log-entries.update');
Route::get('log-entries/{log_entry}/delete', [LogEntryController::class, 'delete'])->name('log-entries.delete');
Route::delete('log-entries/{log_entry}', [LogEntryController::class, 'destroy'])->name('log-entries.destroy');


// User routes with role-based access
Route::middleware('auth')->group(function () {
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
    Route::put('profile', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.updateProfile');
    
    // Student-only PDF export routes
    Route::middleware('role:student')->group(function () {
        Route::get('export', [App\Http\Controllers\UserController::class, 'showExportForm'])->name('users.showExport');
        Route::get('export/logbook', [App\Http\Controllers\UserController::class, 'exportLogbook'])->name('users.exportLogbook');
    });
    
    // Admin-only user management routes
    Route::middleware('role:admin')->group(function () {
        Route::get('users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
        Route::post('users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy');
        
        // Soft delete management
        Route::get('users/trashed', [App\Http\Controllers\UserController::class, 'trashed'])->name('users.trashed');
        Route::post('users/{id}/restore', [App\Http\Controllers\UserController::class, 'restore'])->name('users.restore');
        Route::delete('users/{id}/force', [App\Http\Controllers\UserController::class, 'forceDestroy'])->name('users.force-destroy');
    });
});


Route::get('reflections', [WeeklyReflectionController::class, 'index'])->name('reflections.index');
Route::get('reflections/create', [WeeklyReflectionController::class, 'create'])->name('reflections.create');
Route::post('reflections/create', [WeeklyReflectionController::class, 'store'])->name('reflections.store');
Route::get('reflections/{reflection}', [WeeklyReflectionController::class, 'show'])->name('reflections.show');
Route::get('reflections/{reflection}/edit', [WeeklyReflectionController::class, 'edit'])->name('reflections.edit');
Route::post('reflections/{reflection}/edit', [WeeklyReflectionController::class, 'update'])->name('reflections.update');
Route::get('reflections/{reflection}/delete', [WeeklyReflectionController::class, 'delete'])->name('reflections.delete');
Route::delete('reflections/{reflection}', [WeeklyReflectionController::class, 'destroy'])->name('reflections.destroy');


Route::get('project-entries', [App\Http\Controllers\ProjectEntryController::class, 'index'])->name('project-entries.index');
Route::get('project-entries/create', [App\Http\Controllers\ProjectEntryController::class, 'create'])->name('project-entries.create');
Route::post('project-entries', [App\Http\Controllers\ProjectEntryController::class, 'store'])->name('project-entries.store');
Route::get('project-entries/{projectEntry}', [App\Http\Controllers\ProjectEntryController::class, 'show'])->name('project-entries.show');
Route::get('project-entries/{projectEntry}/edit', [App\Http\Controllers\ProjectEntryController::class, 'edit'])->name('project-entries.edit');
Route::post('project-entries/{projectEntry}/edit', [App\Http\Controllers\ProjectEntryController::class, 'update'])->name('project-entries.update');
Route::get('project-entries/{projectEntry}/delete', [App\Http\Controllers\ProjectEntryController::class, 'delete'])->name('project-entries.delete');
Route::delete('project-entries/{projectEntry}', [App\Http\Controllers\ProjectEntryController::class, 'destroy'])->name('project-entries.destroy');


// Supervisor routes
Route::middleware(['auth', 'role:supervisor,admin'])->group(function () {
    Route::get('supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
    Route::get('supervisor/pending-entries', [SupervisorController::class, 'pendingEntries'])->name('supervisor.pendingEntries');
    Route::get('supervisor/pending-project-entries', [SupervisorController::class, 'pendingProjectEntries'])->name('supervisor.pendingProjectEntries');
    Route::get('supervisor/pending-reflections', [SupervisorController::class, 'pendingReflections'])->name('supervisor.pendingReflections');
    Route::post('supervisor/approve-entry/{entry}', [SupervisorController::class, 'approveEntry'])->name('supervisor.approveEntry');
    Route::post('supervisor/approve-project-entry/{projectEntry}', [SupervisorController::class, 'approveProjectEntry'])->name('supervisor.approveProjectEntry');
    Route::post('supervisor/sign-reflection/{reflection}', [SupervisorController::class, 'signReflection'])->name('supervisor.signReflection');
});


Route::get('students/{student}/export', [LogEntryController::class, 'exportLogbook'])->name('students.export');
