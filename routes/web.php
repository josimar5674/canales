<?php

use Illuminate\Support\Facades\Route;
use App\Models\Channel;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChannelController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard/{channel?}', function (Channel $channel = null) {

    $channels = Channel::all();

    if (!$channel) {

        $channel = $channels->first();

    }

    $messages = $channel

        ? $channel->messages()->with('user')->latest()->get()

        : collect();

    return view('dashboard', compact(

        'channels',

        'channel',

        'messages'

    ));

})->middleware(['auth'])->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::middleware([
    'auth',
    'admin'
])->group(function () {

    Route::resource('users', UserController::class);

});


Route::middleware([
    'auth',
    'admin'
])->group(function () {

    Route::resource('users', UserController::class);

});


Route::middleware([
    'auth',
    'admin'
])->group(function () {

    Route::resource('channels', ChannelController::class);

});