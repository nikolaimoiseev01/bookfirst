<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ShortLink;

Route::middleware(['shortener.key', 'throttle:10,1'])->post('/short-links', function (Request $request) {
    $request->validate([
        'url' => ['required', 'url'],
    ]);

    $link = ShortLink::create([
        'original_url' => $request->url,
        'code' => Str::random(6),
        'clicks' => 0,
    ]);

    return response()->json([
        'short_url' => url($link->code),
    ]);
});
