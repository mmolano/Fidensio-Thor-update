<?php

namespace App\Facades\Service;

use Illuminate\Support\Facades\Facade;

class Pressing extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'pressing';
    }
}
