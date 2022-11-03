<?php

namespace Mmedia\LaravelSpa;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mmedia\LaravelSpa\Skeleton\SkeletonClass
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
        return 'laravel-spa';
    }
}
