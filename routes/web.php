<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\LoanTypeController;
use App\Http\Controllers\Backend\LoanNameController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\RegistrationController;
use App\Http\Controllers\Frontend\ApplyController;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/apply-show', [ApplyController::class, 'show'])->name('frontend.apply.show');
Route::get('/loan-apply/{id}', [ApplyController::class, 'applyForm'])
    ->name('frontend.loan.apply.form');

// Store loan application
Route::post('/loan-apply/store', [ApplyController::class, 'store'])
    ->name('frontend.loan.apply.store');
// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout (protected)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Registration
Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

/*
|--------------------------------------------------------------------------
| Frontend User Profile (Protected Routes)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [RegistrationController::class, 'profile'])->name('profile.view');
    Route::get('/profile/edit', [RegistrationController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [RegistrationController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->name('admin.')->group(function () {
    // PUBLIC
Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// PROTECTED
Route::middleware(['auth.session'])->group(function () {
    // -------- Dashboard --------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//frontend user management
Route::get('/users', [RegistrationController::class, 'index'])->name('registration.index');
    Route::delete('/users/{id}', [RegistrationController::class, 'destroy'])->name('registration.delete');
        // Loan Type
        Route::get('/loan-type', [LoanTypeController::class, 'index'])->name('loan.type.index');
        Route::post('/loan-type/store', [LoanTypeController::class, 'store'])->name('loan.type.store');
        Route::get('/loan-type/edit/{id}', [LoanTypeController::class, 'edit'])->name('loan.type.edit');
        Route::put('/loan-type/update/{id}', [LoanTypeController::class, 'update'])->name('loan.type.update');
        Route::delete('/loan-type/delete/{id}', [LoanTypeController::class, 'destroy'])->name('loan.type.delete');

Route::get('/loan-applications', [ApplyController::class, 'index'])
    ->name('loan.applications');
        // Loan Name
        Route::get('/loan-name', [LoanNameController::class, 'index'])->name('loan.name.index');
        Route::post('/loan-name/store', [LoanNameController::class, 'store'])->name('loan.name.store');
        Route::get('/loan-name/{id}/edit', [LoanNameController::class, 'edit'])->name('loan.name.edit');
        Route::put('/loan-name/{id}', [LoanNameController::class, 'update'])->name('loan.name.update');
        Route::delete('/loan-name/{id}', [LoanNameController::class, 'destroy'])->name('loan.name.delete');
    });

});