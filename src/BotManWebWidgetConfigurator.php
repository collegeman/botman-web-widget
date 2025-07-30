<?php

namespace Collegeman\BotManWebWidget;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Contracts\View\View;
use Collegeman\BotManWebWidget\Contracts\BotManWebWidgetConfigurator as BotManWebWidgetConfiguratorContract;

class BotManWebWidgetConfigurator implements BotManWebWidgetConfiguratorContract
{
    protected Application $app;

    protected array $config;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function echoChannel(): string
    {
        $echoChannelConfig = data_get($this->config(), 'echoChannel');

        return str_replace('$userId', $this->userId(), $echoChannelConfig);
    }

    public function echoEventName(): string
    {
        return basename(data_get($this->config(), 'echoEventClass'));
    }

    public function userId(): string
    {
        return Auth::id() ?? Str::uuid();
    }

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  \Illuminate\Contracts\Support\Arrayable|array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = []): View|ViewFactory
    {
        $factory = $this->app->make(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make('botman-web-widget::' . $view, $data, $mergeData);
    }

    protected function render($view, $data = [], $mergeData = []): string
    {
        return $this->view($view, $data, $mergeData)->render();
    }

    public function getClientConfig(array $config = []): array
    {
        return array_merge($this->config(), [
            'userId' => self::userId(),
            'echoChannel' => self::echoChannel(),
            'echoEventName' => self::echoEventName(),
        ], $config);
    }

    public function config($name = null, $value = null): mixed
    {
        if (empty($this->config)) {
            $config = config('botman-web-widget');

            $this->config = array_merge([
                'icons' => [
                    'back' => $this->render('icons.arrow-left', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'bot' => $this->render('icons.bot', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'close' => $this->render('icons.close', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'closed' => $this->render('icons.comment', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'email' => $this->render('icons.email', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'open' => $this->render('icons.chevron-down', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'search' => $this->render('icons.search', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                    'user' => $this->render('icons.user', [
                        'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                    ]),
                ],
            ], $config);
        }

        if (!empty($name)) {
            if (is_array($name)) {
                $this->config = array_merge($this->config, $name);
            } elseif (!empty($value)) {
                data_set($this->config, $name, $value);
                return $this;
            }
            return data_get($this->config, $name);
        }

        return $this->config;
    }

    public function widget(): string
    {
        return $this->render('widget', ['config' => $this->config]);
    }

    public function asset($path): string
    {
        return $this->hotAsset($path) ?: $this->buildAsset($path);
    }

    protected function buildAsset($path): string
    {
        $manifest = $this->app->publicPath('vendor/botman-web-widget/manifest.json');
        if (file_exists($manifest)) {
            $manifest = json_decode(file_get_contents($manifest), true);
            $asset = $manifest[$path];
            // TODO: don't rely on this helper function
            return asset('vendor/botman-web-widget/'.$asset['file']);
        }
        throw new \Exception('Botman Web Widget Manifest file not found; run `php artisan vendor:publish --tag=botman-web-widget-assets` to generate it.');
    }

    protected function hotAsset($path): string
    {
        $hot = __DIR__.'/../public/hot';
        if (file_exists($hot)) {
            return file_get_contents($hot).'/'.ltrim($path, '/');
        }
        return false;
    }
}