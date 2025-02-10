<?php
use Illuminate\Http\Request;

Route::get(config('botman-web-widget.frameEndpoint'), function (Request $request) {
    return view('botman-web-widget::chat', [
        'config' => [
            'isMobile' => $request->mobile
        ]
    ]);
})->name('botman-web-widget.chat');

Route::get(config('botman-web-widget.beaconEndpoint'), function (Request $request) {
    return view('botman-web-widget::beacon', [
        'config' => [
            'isMobile' => $request->mobile
        ]
    ]);
})->name('botman-web-widget.beacon');
