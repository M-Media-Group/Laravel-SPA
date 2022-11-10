<?php

namespace Mmedia\LaravelSpa\Tests;

use Mmedia\LaravelSpa\LaravelSpaServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Automatically enables package discoveries.
     *
     * @var bool
     */
    // protected $enablesPackageDiscoveries = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelSpaServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
