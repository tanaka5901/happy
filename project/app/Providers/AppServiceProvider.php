<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OfficeStockService;
use App\Services\OfficePurchasesService;
use App\Services\OfficeExpensesService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('OfficeStockService', OfficeStockService::class);
        $this->app->bind('OfficePurchasesService', OfficePurchasesService::class);
        $this->app->bind('OfficeExpensesService', OfficeExpensesService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
