<?php

use Illuminate\Support\Facades\Route;
use App\Models\Channel;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ChannelController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard/{channel?}', function (Channel $channel = null) {

    // ADMIN VE TODO
    if (auth()->user()->role === 'admin') {

       if (auth()->user()->role === 'admin') {

    $channels = Channel::all();

} else {

    $channels = auth()->user()
        ->channels()
        ->where('active', true)
        ->get();

}

    } else {

        // USUARIO SOLO VE SUS CANALES
        $channels = auth()->user()->channels;

    }

    // SI NO VIENE CANAL, TOMAR EL PRIMERO DISPONIBLE
    if (!$channel) {

        $channel = $channels->first();

    }
    if (
    auth()->user()->role !== 'admin'
    &&
    !$channel->active
) {

    abort(403);

}

    // BLOQUEAR ACCESO MANUAL POR URL
    if (
        $channel
        &&
        auth()->user()->role !== 'admin'
        &&
        !$channels->contains($channel->id)
    ) {

        abort(403);

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

    Route::resource('channels', ChannelController::class);

});