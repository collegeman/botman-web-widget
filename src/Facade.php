<?php

namespace Collegeman\BotmanWebWidget;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * @see \Collegeman\BotmanWebWidget\Skeleton\SkeletonClass
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
        return 'botman-web-widget';
    }
}
