<?php

namespace Collegeman\BotmanWebWidget;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \Collegeman\BotmanWebWidget\Botman
 */
class Facade extends BaseFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Botman::class;
    }
}
