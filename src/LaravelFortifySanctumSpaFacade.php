<?php

namespace Mmedia\LaravelFortifySanctumSpa;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Mmedia\LaravelFortifySanctumSpa\Skeleton\SkeletonClass
 */
class LaravelFortifySanctumSpaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-fortify-sanctum-spa';
    }
}
