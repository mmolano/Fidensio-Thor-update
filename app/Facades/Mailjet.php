<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Mailjet extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'mailjet';
    }
}
