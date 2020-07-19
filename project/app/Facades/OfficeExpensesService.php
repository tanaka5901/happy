<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OfficeExpensesService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'OfficeExpensesService';
    }
}