<?php

namespace Collegeman\BotmanWebWidget;

class BotmanWebWidgetConfigurator
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = array_merge([
            'icons' => [
                'arrowLeft' => view('botman-web-widget::icons.arrow-left', [
                    'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                ])->render(),
                'open' => view('botman-web-widget::icons.chevron-down', [
                    'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                ])->render(),
                'closed' => view('botman-web-widget::icons.comment', [
                    'stroke' => data_get($config, 'beaconLabelColor', '#ffffff'),
                ])->render(),
            ],
        ], $config);
    }

    public function config($name = null, $value = null): mixed
    {
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

    public function widget()
    {
        return view('botman-web-widget::widget', ['config' => $this->config])->render();
    }

    public function asset($path)
    {
        return $this->hotAsset($path) ?: $this->buildAsset($path);
    }

    public function buildAsset($path)
    {
        $manifest = public_path('vendor/botman-web-widget/manifest.json');
        if (file_exists($manifest)) {
            $manifest = json_decode(file_get_contents($manifest), true);
            $asset = $manifest[$path];
            return asset('vendor/botman-web-widget/'.$asset['file']);
        }
        throw new \Exception('Botman Web Widget Manifest file not found; run `php artisan vendor:publish --tag=botman-web-widget-assets` to generate it.');
    }

    public function hotAsset($path)
    {
        $hot = __DIR__.'/../public/hot';
        if (file_exists($hot)) {
            return file_get_contents($hot).'/'.ltrim($path, '/');
        }
        return false;
    }
}