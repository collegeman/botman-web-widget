<?php

namespace Collegeman\BotmanWebWidget;

class Botman
{
    public function widget(array $config = null)
    {
        return view('botman-web-widget::widget', ['config' => array_merge(config('botman-web-widget'), $config ?? [])])->render();
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