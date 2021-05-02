<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Stripe extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'stripe';
    }
}
