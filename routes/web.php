<?php

Route::get(config('botman-web-widget.frameEndpoint'), function () {
    return view('botman-web-widget::chat');
})->name('botman-web-widget.chat');

Route::get(config('botman-web-widget.beaconEndpoint'), function () {
    return view('botman-web-widget::beacon');
})->name('botman-web-widget.beacon');
