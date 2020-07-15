<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BranchStockService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'BranchStockService';
    }
}