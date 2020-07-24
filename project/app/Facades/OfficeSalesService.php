<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class OfficeSalesService extends Facade
{
    protected static function getFacadeAccessor() {
        return 'OfficeSalesService';
    }
}