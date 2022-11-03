<?php

namespace Mmedia\LaravelSpa;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mmedia\LaravelSpa\LaravelSpa
 */
class LaravelSpaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'LaravelSpa';
    }
}
