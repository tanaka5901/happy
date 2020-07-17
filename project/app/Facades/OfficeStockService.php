<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OfficeStockService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'OfficeStockService';
    }
}