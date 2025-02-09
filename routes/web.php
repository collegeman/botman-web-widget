<?php

Route::get(config('botman-web-widget.frameEndpoint'), function () {
    return view('botman-web-widget::host');
})->name('botman-web-widget.host');
