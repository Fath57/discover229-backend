<?php

namespace App\Traits\Common;

use App\Scopes\AlphabeticFilterScope;

trait AlphabeticallyOrderTrait
{
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new AlphabeticFilterScope);
    }
}
