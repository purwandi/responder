<?php

namespace Purwandi\Responder;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Purwandi\Responder\Responder
 */
class ResponderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'responder';
    }
}
