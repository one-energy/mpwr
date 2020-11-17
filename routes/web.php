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
use App\Http\Controllers\Castle\RatesController;
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
        
        Route::prefix('offices')->middleware('offices')->name('offices.')->group(function () {
            Route::get('/', [OfficeController::class, 'index'])->name('index');
            Route::get('/create', [OfficeController::class, 'create'])->name('create');
            Route::post('/create', [OfficeController::class, 'store'])->name('store');
            Route::get('/{office}/edit', [OfficeController::class, 'edit'])->name('edit');
            Route::put('{office}', [OfficeController::class, 'update'])->name('update');
            Route::delete('{office}', [OfficeController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('rates')->middleware('rates')->name('rates.')->group(function () {
            Route::get('/',[RatesController::class, 'index'])->name('index');
        });

        Route::prefix('regions')->middleware('regions')->name('regions.')->group(function () {
            Route::get('/', [RegionController::class, 'index'])->name('index');
            Route::get('/create', [RegionController::class, 'create'])->name('create');
            Route::post('/create', [RegionController::class, 'store'])->name('store');
            Route::get('/{region}/edit', [RegionController::class, 'edit'])->name('edit');
            Route::put('/{region}', [RegionController::class, 'update'])->name('update');
            Route::delete('/{region}', [RegionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('departments')->middleware('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('create');
            Route::post('/create', [DepartmentController::class, 'store'])->name('store');
            Route::get('/{deparment}/edit', [DepartmentController::class, 'edit'])->name('edit');
            Route::put('/{deparment}', [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{deparment}', [DepartmentController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('incentives')->middleware('incentives')->name('incentives.')->group(function () {
            Route::get('/', [ManageIncentivesController::class, 'index'])->name('index');
            Route::get('/create', [ManageIncentivesController::class, 'create'])->name('create');
            Route::post('/create', [ManageIncentivesController::class, 'store'])->name('store');
            Route::get('/{incentive}/edit', [ManageIncentivesController::class, 'edit'])->name('edit');
            Route::put('/{incentive}', [ManageIncentivesController::class, 'update'])->name('update');
            Route::delete('/{incentive}', [ManageIncentivesController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('manage-trainings')->middleware('incentives')->name('manage-trainings.')->group(function () {
            Route::get('/list/{department?}/{section?}', [TrainingController::class, 'manageTrainings'])->name('index');
            Route::post('/{section?}/create-section', [TrainingController::class, 'storeSection'])->name('storeSection');
            Route::put('/{section?}/update-section', [TrainingController::class, 'updateSection'])->name('updateSection');
            Route::post('/{section?}/create-content', [TrainingController::class, 'storeContent'])->name('storeContent');
            Route::post('/{content}/update-content', [TrainingController::class, 'updateContent'])->name('updateContent');
            Route::post('/changeDepartment', [TrainingController::class, 'changeDepartment'])->name('changeDepartment');
            Route::delete('/{section}', [TrainingController::class, 'deleteSection'])->name('deleteSection');
        });
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
    Route::delete('/customers/{customer}', [CustomerController::class, 'delete'])->name('customers.delete');
    Route::get('/scoreboard', ScoreboardController::class)->name('scoreboard');
    Route::get('/trainings/{department?}/{section?}', [TrainingController::class, 'index'])->name('trainings.index');
    Route::get('/incentives', IncentivesController::class)->name('incentives');
    Route::get('/number-tracking', [NumberTrackingController::class, 'index'])->name('number-tracking.index');
    Route::get('/number-tracking/create', [NumberTrackingController::class, 'create'])->name('number-tracking.create');
    Route::post('/number-tracking/create', [NumberTrackingController::class, 'store'])->name('number-tracking.store');

    Route::post('/get-offices-managers/{departmentId}', [UsersController::class, 'getOfficesManager'])->name('getOfficesManager');
    Route::post('/get-regions-managers/{departmentId}', [UsersController::class, 'getRegionsManager'])->name('getRegionsManager');
    Route::post('/get-regions/{departmentId?}', [RegionController::class, 'getRegions'])->name('getRegions');
    Route::post('/get-departments', [DepartmentController::class, 'getDepartments'])->name('getDepartments');
    Route::post('/get-offices/{departmentId?}', [OfficeController::class, 'getOffices'])->name('getOffices');

});

