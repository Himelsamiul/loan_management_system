<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LoanType;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share all active loan types with the header view
        View::composer('frontend.partials.header', function ($view) {
            $loanTypes = LoanType::where('status', 'active')->get();
            $view->with('loanTypes', $loanTypes);
        });
    }
}
