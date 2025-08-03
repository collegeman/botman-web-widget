<?php

namespace Collegeman\BotManWebWidget;

use Collegeman\BotManWebWidget\Console\Commands\ChatCommand;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Collegeman\BotManWebWidget\Contracts\BotManWebWidgetConfigurator as BotManWebWidgetConfiguratorContract;

class BotManWebWidgetServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'botman-web-widget');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'botman-web-widget');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('botman-web-widget.php'),
            ], 'botman-web-widget-config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/botman-web-widget'),
            ], 'botman-web-widget-views');

            $this->publishes([
                __DIR__.'/../public/build' => public_path('vendor/botman-web-widget'),
            ], 'botman-web-widget-assets');

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/botman-web-widget'),
            ], 'botman-web-widget-lang');*/

            // Registering package commands.
            $this->commands([
                ChatCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'botman-web-widget');

        // Register the main class to use with the facade
        $this->app->singleton(BotManWebWidgetConfiguratorContract::class, fn () => new BotManWebWidgetConfigurator($this->app));

        Blade::directive('botman', function (string $expression) {
            return "<?php echo BotManWebWidget::widget({$expression}); ?>";
        });
    }
}
