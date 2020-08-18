<?php

namespace App\Http\Controllers\VKMA;

use App\Card;
use App\Goal;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\GoalResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use SwooleTW\Http\Websocket\Facades\Websocket;

class CardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param int $id
     * @param Request $request
     * @return CardResource
     * @throws ValidationException
     */
    public function checkin(int $id, Request $request): CardResource
    {
        $this->validate($request, [
            'hash' => 'required|string'
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $card = Card::query()->findOrFail($id);

        if ($user->cannot('checkin', $card)) {
            abort(403);
        }

        $card->checkinBy($user, $request->input('hash'));

        $card->refresh();

        Websocket::broadcast()->to($card->getWsRoomKey())->emit('card-checkined', (new CardResource($card))->resolve());

        return new CardResource($card);
    }

    public function destroy(int $id) {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $card = Card::query()->findOrFail($id);

        if ($user->cannot('update', $card)) {
            abort(403);
        }

        $card->delete();

        return response(null, 204);
    }
}
