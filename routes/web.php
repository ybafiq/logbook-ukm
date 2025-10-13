<?php

use Illuminate\Support\Facades\Route;
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
Route::post('log-entries/create', [LogEntryController::class, 'store'])->name('log-entries.store');
Route::get('log-entries/{log_entry}', [LogEntryController::class, 'show'])->name('log-entries.show');
Route::get('log-entries/{log_entry}/edit', [LogEntryController::class, 'edit'])->name('log-entries.edit');
Route::post('log-entries/{log_entry}/edit', [LogEntryController::class, 'update'])->name('log-entries.update');
Route::get('log-entries/{log_entry}/delete', [LogEntryController::class, 'delete'])->name('log-entries.delete');


Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');


Route::get('reflections', [WeeklyReflectionController::class, 'index'])->name('reflections.index');
Route::get('reflections/create', [WeeklyReflectionController::class, 'create'])->name('reflections.create');
Route::post('reflections/create', [WeeklyReflectionController::class, 'store'])->name('reflections.store');
Route::get('reflections/{reflection}', [WeeklyReflectionController::class, 'show'])->name('reflections.show');
Route::get('reflections/{reflection}/edit', [WeeklyReflectionController::class, 'edit'])->name('reflections.edit');
Route::post('reflections/{reflection}/edit', [WeeklyReflectionController::class, 'update'])->name('reflections.update');
Route::get('reflections/{reflection}/delete', [WeeklyReflectionController::class, 'delete'])->name('reflections.delete');


Route::post('supervisor/approve-entry/{entry}', [SupervisorController::class, 'approveEntry'])->name('supervisor.approveEntry');
Route::post('supervisor/sign-reflection/{reflection}', [SupervisorController::class, 'signReflection'])->name('supervisor.signReflection');


Route::get('students/{student}/export', [LogEntryController::class, 'exportLogbook'])->name('students.export');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
