<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\AtasanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyUserController;
use App\Http\Controllers\TemplateEmailController;
use Illuminate\Support\Facades\Route;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('user/index', function () {
//     return view('user.index');
// });


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
Route::middleware(['auth', 'role:admin|supervisor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('alumni/export', [AlumniController::class, 'export'])->name('alumni.export');
    Route::post('alumni/import', [AlumniController::class, 'import'])->name('alumni.import');
    Route::delete('alumni/delete/{id}', [AlumniController::class, 'destroy'])->name('admin.alumni.destroy'); //hapus alumni
    Route::post('atasan/import', [AtasanController::class, 'import'])->name('atasan.import');
    Route::get('atasan/export', [AtasanController::class, 'export'])->name('atasan.export');
    Route::get('atasan/details/{id}', [AtasanController::class, 'details'])->name('atasan.details');
    Route::delete('atasan/delete/{id}', [AtasanController::class, 'destroy'])->name('admin.atasan.destroy');
    Route::resource('alumni', AlumniController::class);
    Route::resource('atasan', AtasanController::class);
    Route::post('survey/import', [SurveyController::class, 'import'])->name('survey.import');
    Route::get('survey/add_question/{id}', [SurveyController::class, 'add_question'])->name('survey.add_question');
    Route::get('survey/details/{id}', [SurveyController::class, 'details'])->name('survey.details');
    Route::post('survey/create_question', [SurveyController::class, 'create_question'])->name('survey.create_question');
    Route::post('survey/duplicate/{id}', [SurveyController::class, 'duplicate'])->name('survey.duplicate');

    Route::resource('survey', SurveyController::class);
    // Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    
    Route::get('monitoring/export/{surveyId}', [MonitoringController::class, 'export'])->name('monitoring.export');
    Route::get('monitoring/details/{id}', [MonitoringController::class, 'details'])->name('monitoring.details');
    Route::resource('monitoring', MonitoringController::class);
    Route::get('monitoring/grafik/{id}', [MonitoringController::class, 'grafik'])->name('monitoring.grafik');
    Route::get('monitoring/chart-data/{surveyId}/{questionId}', [MonitoringController::class, 'getChartData'])->name('monitoring.chartData');
    // Route::resource('profile', ProfileController::class);
    Route::get('profile/edit', [ProfileController::class, 'editAdmin'])->name('profile.edit');
    Route::put('profile/update', [ProfileController::class, 'updateAdmin'])->name('profile.update');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::resource('user', ProfileController::class);
    Route::get('search_user',[SurveyUserController::class, 'search_user'])->name('search_user');
    Route::post('survey/add_user',[SurveyUserController::class, 'add_user'])->name('survey.add_user');
    Route::post('survey/add_alumni_by_graduation_year',[SurveyUserController::class, 'add_alumni_by_graduation_year'])->name('survey.add_alumni_by_graduation_year');
    Route::get('get_graduation_years',[SurveyUserController::class, 'get_graduation_years'])->name('get_graduation_years');
    Route::post('send_email/{surveyId}',[SurveyUserController::class, 'sendEmail'])->name('send_email');
    Route::post('send_reminders/{surveyId}',[SurveyUserController::class, 'sendReminders'])->name('send_reminders');
    Route::post('send_thank_you/{surveyUserId}',[SurveyUserController::class, 'sendThankYou'])->name('send_thank_you');
    Route::post('send_bulk_thank_you/{surveyId}',[SurveyUserController::class, 'sendBulkThankYou'])->name('send_bulk_thank_you');
    Route::delete('survey_user/destroy/{survey_user_id}', [SurveyUserController::class, 'destroy'])->name('survey_user.destroy');
    Route::get('survey/template_email', [TemplateEmailController::class, 'template_email'])->name('survey.template_email'); //template email
    Route::get('template_email', [TemplateEmailController::class, 'template_email'])->name('template_email.index'); //template email index
    Route::post('template_email/update', [TemplateEmailController::class, 'update'])->name('template_email.update');
    Route::get('template_email/preview', [TemplateEmailController::class, 'preview'])->name('template_email.preview');
});

// User route
Route::middleware(['auth', 'role:alumni|atasan'])->name('user.')->group(function () {
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('survey/{id}', [SurveyUserController::class, 'surveyUserPertanyaan'])->name('survey.survey');
    Route::post('survey/{id}', [SurveyUserController::class, 'saveSurvey'])->name('survey.save');
    Route::get('monitoring', [SurveyUserController::class, 'index'])->name('monitoring.index');
});



require __DIR__ . '/auth.php';
