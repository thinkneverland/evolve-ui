<?php

namespace Thinkneverland\EvolveUi\Facades;

use Illuminate\Support\Facades\Facade;

class EvolveUi extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'evolve-ui';
    }
}
