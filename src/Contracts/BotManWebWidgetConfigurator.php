<?php

namespace Collegeman\BotManWebWidget\Contracts;

interface BotManWebWidgetConfigurator
{
    /**
     * Retrieve the User ID for the authenticated session.
     *
     * @return string
     */
    public function userId(): string;

    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function view($view = null, $data = [], $mergeData = []): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory;

    /**
     * Get or set configuration parameters.
     *
     * When only a name is provided, returns the configuration value.
     * When both name and value are provided, it sets the configuration.
     *
     * @param mixed $name
     * @param mixed $value
     * @return mixed
     */
    public function config($name = null, $value = null): mixed;


    /**
     * @return array The configuration array for the widget.
     */
    public function getClientConfig(): array;

    /**
     * Render the embeddable widget view.
     *
     * @return string
     */
    public function widget(): string;

    /**
     * Get the URL for the given asset.
     *
     * @param string $path
     * @return string
     */
    public function asset($path): string;

    /**
     * @return string The name of the echo channel for Botman messages
     */
    public function echoChannel(): string;

    /**
     * @return string The classname for for Botman message events
     */
    public function echoEventName(): string;

}
