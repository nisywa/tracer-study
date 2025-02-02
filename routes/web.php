<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::prefix('admin')->name('admin.')->group(function () {
//         Route::resource('survey', SurveyController::class)->middleware('role:admin');
//         Route::get('dashboard', [DashboardController::class,'index'])->middleware('role:admin');
//     });

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Admin route
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('alumni/export', [AlumniController::class, 'export'])->name('alumni.export');
    Route::post('alumni/import', [AlumniController::class, 'import'])->name('alumni.import');
    Route::post('atasan/import', [AtasanController::class, 'import'])->name('atasan.import');
    Route::get('atasan/export', [AtasanController::class, 'export'])->name('atasan.export');
    Route::resource('alumni', AlumniController::class);
    Route::resource('atasan', AtasanController::class);
    Route::get('survey/add_question/{id}', [SurveyController::class, 'add_question'])->name('survey.add_question');
    Route::resource('survey', SurveyController::class);
    Route::resource('monitoring', MonitoringController::class);
    // Route::resource('profile', ProfileController::class);
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('user', ProfileController::class);
});

// User route
Route::middleware(['auth', 'role:alumni|atasan'])->name('user.')->group(function () {
    // Route::get('/', function () {
    //     return view('welcome');
    // })->name('user.dashboard');
});



require __DIR__.'/auth.php';
