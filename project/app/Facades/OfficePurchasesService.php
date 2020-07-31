<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OfficePurchasesService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'OfficePurchasesService';
    }
}