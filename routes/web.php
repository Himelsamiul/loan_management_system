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
use App\Http\Controllers\Backend\GiveLoanController;

/*
|--------------------------------------------------------------------------
| Frontend Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'home'])->name('home');
 Route::get('/apply-show', [ApplyController::class, 'show'])->name('frontend.apply.show');
// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Registration
Route::get('/register', [RegistrationController::class, 'create'])->name('register.create');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

/*
|--------------------------------------------------------------------------
| Frontend Protected Routes (after login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [RegistrationController::class, 'profile'])->name('profile.view');
    Route::get('/profile/edit', [RegistrationController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [RegistrationController::class, 'update'])->name('profile.update');

    // Loan apply
   
    Route::get('/loan-apply/{id}', [ApplyController::class, 'applyForm'])->name('frontend.loan.apply.form');
    Route::post('/loan-apply/store', [ApplyController::class, 'store'])->name('frontend.loan.apply.store');
    Route::post('/loan-apply/review', [ApplyController::class, 'review'])->name('frontend.loan.apply.review');

    // View loan installments
    Route::get('/loan/installments/{id}', [RegistrationController::class, 'viewInstallments'])
        ->name('frontend.loan.installments');
});

/*
|--------------------------------------------------------------------------
| Backend Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {

    // Admin public login/logout
    Route::get('/login',  [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth.session'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Frontend user management
        Route::get('/users', [RegistrationController::class, 'index'])->name('registration.index');
        Route::delete('/users/{id}', [RegistrationController::class, 'destroy'])->name('registration.delete');

        // Loan Type
        Route::get('/loan-type', [LoanTypeController::class, 'index'])->name('loan.type.index');
        Route::post('/loan-type/store', [LoanTypeController::class, 'store'])->name('loan.type.store');
        Route::get('/loan-type/edit/{id}', [LoanTypeController::class, 'edit'])->name('loan.type.edit');
        Route::put('/loan-type/update/{id}', [LoanTypeController::class, 'update'])->name('loan.type.update');
        Route::delete('/loan-type/delete/{id}', [LoanTypeController::class, 'destroy'])->name('loan.type.delete');

        // Loan Name
        Route::get('/loan-name', [LoanNameController::class, 'index'])->name('loan.name.index');
        Route::post('/loan-name/store', [LoanNameController::class, 'store'])->name('loan.name.store');
        Route::get('/loan-name/{id}/edit', [LoanNameController::class, 'edit'])->name('loan.name.edit');
        Route::put('/loan-name/{id}', [LoanNameController::class, 'update'])->name('loan.name.update');
        Route::delete('/loan-name/{id}', [LoanNameController::class, 'destroy'])->name('loan.name.delete');

        // Loan Applications
        Route::get('/loan-applications', [ApplyController::class, 'index'])->name('loan.applications');
        Route::patch('/loan/{id}/status', [ApplyController::class, 'updateStatus'])->name('loan.updateStatus');

        // Give Loan
        Route::get('/give-loan', [GiveLoanController::class, 'index'])->name('loan.approved');
        Route::post('/give-loan/{id}', [GiveLoanController::class, 'giveLoan'])->name('loan.give');
        Route::get('/given-loans', [GiveLoanController::class, 'givenLoans'])->name('loan.given');
        Route::get('/loan/details/{id}', [GiveLoanController::class, 'loanDetails'])->name('loan.details');

        Route::post('/loan/pay/{id}', [GiveLoanController::class, 'payInstallment'])
    ->name('loan.pay');

    });
});
