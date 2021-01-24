<?php

namespace Savannabits\JetstreamInertiaGenerator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Savannabits\JetstreamInertiaGenerator\Skeleton\SkeletonClass
 */
class JetstreamInertiaGeneratorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'jetstream-inertia-generator';
    }
}
