<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\BranchStockService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('BranchStockService', BranchStockService::class);
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
