<?php

namespace Mmedia\LaravelSpa;

class LaravelSpa
{
    // Build your next great package.

    public function getSpaUrl()
    {
        return config('laravel-spa.spa_url');
    }

    public function getRoutePaths()
    {
        return config('laravel-spa.route_paths');
    }

    public function getRoutePath(string $path): string
    {
        return config('laravel-spa.route_paths.' . $path);
    }

    public function getSpaUrlForPath(string $path): string
    {

        return $this->getSpaUrl() . '/' . $this->getRoutePath($path);
    }
}
