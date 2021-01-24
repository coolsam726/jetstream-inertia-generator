<?php

namespace Savannabits\JetstreamInertiaGenerator\Tests;

use Orchestra\Testbench\TestCase;
use Savannabits\JetstreamInertiaGenerator\JetstreamInertiaGeneratorServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [JetstreamInertiaGeneratorServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
