<?php

use App\Http\Controllers\Auth\ConfirmPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\InvitationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Castle\DepartmentController;
use App\Http\Controllers\Castle\MasterInvitationController;
use App\Http\Controllers\Castle\MastersController;
use App\Http\Controllers\Castle\ResponseMasterInvitationController;
use App\Http\Controllers\Castle\RevokeMasterAccessController;
use App\Http\Controllers\Castle\UsersController;
use App\Http\Controllers\Castle\ManageIncentivesController;
use App\Http\Controllers\Castle\OfficeController;
use App\Http\Controllers\Castle\PermissionController;
use App\Http\Controllers\Castle\RegionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ScoreboardController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\TrainingSettingsController;
use App\Http\Controllers\TrainingSettingsBestPracticesController;
use App\Http\Controllers\TrainingSettingsBestPracticesWhatToSayController;
use App\Http\Controllers\IncentivesController;
use App\Http\Controllers\NumberTrackingController;
use App\Http\Controllers\ProfileChangePasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoUploadController;
use Illuminate\Support\Facades\Route;

//region Authentication and Registration Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::any('logout', [LoginController::class, 'logout'])->name('logout');
Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);
Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
Route::get('register/{token}', [InvitationController::class, 'invite'])->name('register.with-invitation');
Route::post('register/{token}', [InvitationController::class, 'register']);
//endregion

Route::middleware(['auth', 'verified'])->group(function () {
    //region Castle
    Route::post('castle/masters/invite/response', ResponseMasterInvitationController::class)->name('castle.masters.invite.response');

    Route::prefix('castle/')->middleware('castle')->name('castle.')->group(function () {
        Route::get('dashboard', HomeController::class)->name('dashboard');

        Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
        Route::post('users/create', [UsersController::class, 'store'])->name('users.store');
        Route::get('users', [UsersController::class, 'index'])->name('users.index');
        Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::get('users/{user}/reset-password', [UsersController::class, 'requestResetPassword'])->name('users.request-reset-password');
        Route::put('users/{user}/reset-password', [UsersController::class, 'resetPassword'])->name('users.reset-password');
        Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::get('users/{user}', [UsersController::class, 'show'])->name('users.show');

        Route::get('permission', [PermissionController::class, 'index'])->name('permission.index');
        Route::get('permission/{user}/edit', [PermissionController::class, 'edit'])->name('permission.edit');
        Route::put('permission/{user}', [PermissionController::class, 'update'])->name('permission.update');
        
        Route::get('masters', [MastersController::class, 'index'])->name('masters.index');
        Route::get('masters/invite', [MasterInvitationController::class, 'form'])->name('masters.invite');
        Route::post('masters/invite', [MasterInvitationController::class, 'invite']);
        Route::patch('masters/{master}/revoke', RevokeMasterAccessController::class)->name('masters.revoke');

        Route::get('offices', [OfficeController::class, 'index'])->name('offices.index');
        Route::get('offices/create', [OfficeController::class, 'create'])->name('offices.create');
        Route::post('offices/create', [OfficeController::class, 'store'])->name('offices.store');
        Route::get('offices/{office}/edit', [OfficeController::class, 'edit'])->name('offices.edit');
        Route::put('offices/{office}', [OfficeController::class, 'update'])->name('offices.update');
        Route::delete('offices/{office}', [OfficeController::class, 'destroy'])->name('offices.destroy');

        Route::get('regions', [RegionController::class, 'index'])->name('regions.index');
        Route::get('regions/create', [RegionController::class, 'create'])->name('regions.create');
        Route::post('regions/create', [RegionController::class, 'store'])->name('regions.store');
        Route::get('regions/{region}/edit', [RegionController::class, 'edit'])->name('regions.edit');
        Route::put('regions/{region}', [RegionController::class, 'update'])->name('regions.update');
        Route::delete('regions/{region}', [RegionController::class, 'destroy'])->name('regions.destroy');

        Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('departments/create', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('departments/{deparment}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('departments/{deparment}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('departments/{deparment}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

        Route::get('incentives', [ManageIncentivesController::class, 'index'])->name('incentives.index');
        Route::get('incentives/create', [ManageIncentivesController::class, 'create'])->name('incentives.create');
        Route::post('incentives/create', [ManageIncentivesController::class, 'store'])->name('incentives.store');
        Route::get('incentives/{incentive}/edit', [ManageIncentivesController::class, 'edit'])->name('incentives.edit');
        Route::put('incentives/{incentive}', [ManageIncentivesController::class, 'update'])->name('incentives.update');
        Route::delete('incentives/{incentive}', [ManageIncentivesController::class, 'destroy'])->name('incentives.destroy');

        Route::get('/manage-trainings/list/{section?}', [TrainingController::class, 'manageTrainings'])->name('manage-trainings.index');
        Route::post('/manage-trainings/{section?}/create-section', [TrainingController::class, 'storeSection'])->name('manage-trainings.storeSection');
        Route::put('/manage-trainings/{section?}/update-section', [TrainingController::class, 'updateSection'])->name('manage-trainings.updateSection');
        Route::post('/manage-trainings/{section?}/create-content', [TrainingController::class, 'storeContent'])->name('manage-trainings.storeContent');
        Route::post('/manage-trainings/{content}/update-content', [TrainingController::class, 'updateContent'])->name('manage-trainings.updateContent');
        Route::delete('/manage-trainings/{section}', [TrainingController::class, 'deleteSection'])->name('manage-trainings.deleteSection');
        
    });
    //endregion

    Route::get('/', HomeController::class)->name('home');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/show-profile-information', [ProfileController::class, 'index'])->name('profile.show-profile-information');
    Route::get('/profile/{user}/show-modal-profile-information', [ProfileController::class, 'showData'])->name('profile.show-modal-profile-information');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/show', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo-upload', ProfilePhotoUploadController::class)->name('profile.photo-upload');
    Route::put('/profile/change-password', ProfileChangePasswordController::class)->name('profile.change-password');

    Route::resource('/customers', CustomerController::class);
    Route::put('/customers/{customer}/active', [CustomerController::class, 'Active'])->name('customers.active');
    Route::get('/scoreboard', ScoreboardController::class)->name('scoreboard');
    Route::get('/trainings/{section?}', [TrainingController::class, 'index'])->name('trainings.index');
    // Route::get('/trainings/settings', [TrainingSettingsController::class, 'index'])->name('trainings.settings.index');
    // Route::get('/trainings/settings/best-practices', [TrainingSettingsBestPracticesController::class ,'index'])->name('trainings.settings.best-practices.index');
    // Route::get('/trainings/settings/best-practices/what-to-say', [TrainingSettingsBestPracticesWhatToSayController::class, 'index'])->name('trainings.settings.best-practices.what-to-say.index');
    Route::get('/incentives', IncentivesController::class)->name('incentives');
    Route::get('/number-tracking', [NumberTrackingController::class, 'index'])->name('number-tracking.index');
    Route::get('/number-tracking/create', [NumberTrackingController::class, 'create'])->name('number-tracking.create');
    Route::post('/number-tracking/create', [NumberTrackingController::class, 'store'])->name('number-tracking.store');
});

