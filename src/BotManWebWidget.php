<?php

namespace Collegeman\BotManWebWidget;

use Illuminate\Support\Facades\Facade;
use Collegeman\BotManWebWidget\Contracts\BotManWebWidgetConfigurator as BotManWebWidgetConfiguratorContract;

/**
 * @see \Collegeman\BotmanWebWidget\BotmanConfigurator
 */
class BotManWebWidget extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return BotManWebWidgetConfiguratorContract::class;
    }
}
