<?php

use Collegeman\BotManWebWidget\BotManWebWidget;
use Illuminate\Http\Request;

Route::get(BotManWebWidget::config('frameEndpoint'), function (Request $request) {
    return BotManWebWidget::view('chat', ['config' => ['isMobile' => $request->isMobile]]);
})->name('botman-web-widget.chat');

Route::get(BotManWebWidget::config('beaconEndpoint'), function (Request $request) {
    return BotManWebWidget::view('beacon', ['config' => ['isMobile' => $request->isMobile]]);
})->name('botman-web-widget.beacon');