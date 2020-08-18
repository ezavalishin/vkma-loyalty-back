<?php


use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('connect', function ($websocket, Request $request) {
    // called while socket on connect
});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
});

Websocket::on('sub-card', function (\SwooleTW\Http\Websocket\Websocket $websocket, $data) {
    $cardId = $data['cardId'] ?? null;

    if ($cardId) {
        $card = \App\Card::query()->findOrFail($cardId);

        $websocket->join($card->getWsRoomKey());

        $websocket->emit('subbed', (new \App\Http\Resources\CardResource($card))->resolve());
    }
});
