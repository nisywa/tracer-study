<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });

<<<<<<< Updated upstream
=======
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// Admin route
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('alumni/import', [AlumniController::class, 'import'])->name('alumni.import');
    Route::get('alumni/export', [AlumniController::class, 'export'])->name('alumni.export');
    Route::get('alumni/test', function () {
        return auth()->user() ?: 'No user logged in';
    })->name('alumni.test');
    Route::resource('alumni', AlumniController::class);
    Route::resource('atasan', AtasanController::class);
    Route::resource('survey', SurveyController::class);
    Route::resource('monitoring', MonitoringController::class);
    // Route::resource('profile', ProfileController::class);
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('user', ProfileController::class);
>>>>>>> Stashed changes
});

require __DIR__.'/auth.php';
