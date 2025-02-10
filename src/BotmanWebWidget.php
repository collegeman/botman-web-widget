<?php

namespace Collegeman\BotmanWebWidget;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Collegeman\BotmanWebWidget\BotmanConfigurator
 */
class BotmanWebWidget extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BotmanWebWidgetConfigurator::class;
    }
}
