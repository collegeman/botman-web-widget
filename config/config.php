<?php

/**
 * You can place your custom package configuration in here.
 */

return [
    // The URL of the BotMan route / server to use.
    'chatServer' => '/botman',

    // The location of your chat frame URL / route.
    'frameEndpoint' => '/botman/chat',

    // The location of your chat beacon URL / route.
    'beaconEndpoint' => '/botman/beacon',

    // Time format to use.
    'timeFormat' => 'HH:MM',

    // Date-Time format to use.
    'dateTimeFormat' => 'm/d/yy HH:MM',

    // The title to use in the widget.
    'title' => env('BOTMAN_WIDGET_TITLE', env('APP_NAME', 'BotMan Widget')),

    // This is a welcome message that every new user sees when the widget is opened for the first time.
    'introMessage' => null,

    // Input placeholder text.
    'placeholderText' => 'Send a message...',

    // Determine if message times should be shown.
    'displayMessageTime' => true,

    // The main color used in the widget header.
    'mainColor' => '#111111',

    // The color to use for the bubble background.
    'bubbleBackground' => '#408591',

    // The image URL to use in the chat bubble.
    'bubbleAvatarUrl' => null,

    // Height of the opened chat widget on desktops.
    'desktopHeight' => 550,

    // Width of the opened chat widget on desktops.
    'desktopWidth' => 370,

    // Height of the opened chat widget on mobile.
    'mobileHeight' => '100%',

    // Width of the opened chat widget on mobile.
    'mobileWidth' => '100%',

    // The color to use for the beacon badge.
    'beaconColor' => '#111111',

    // The color to use for the beacon badge when hovered.
    'beaconColorHover' => '#2b7fff',

    // The label color for the beacon
    'beaconLabelColor' => '#ffffff',

    // Height to use for embedded videos.
    'videoHeight' => 160,

    // The size of the beacon badge.
    'beaconSize' => 60,

    // Link used for the "about" section in the widget footer.
    'aboutLink' => 'https://github.com/collegeman/botman-web-widget',

    // Text used for the "about" section in the widget footer.
    'aboutText' => 'Powered by BotMan',

    // Optional user-id that get's sent to BotMan. If no ID is given, a random id will be generated on each page-view.
    'userId' => null,
];
