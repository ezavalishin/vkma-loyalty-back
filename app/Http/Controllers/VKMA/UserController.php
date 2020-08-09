<?php

namespace App\Http\Controllers\VKMA;

use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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

    public function me(): UserResource
    {
        return new UserResource(Auth::user());
    }

    public function indexCards(): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $cards = $user->cards()
            ->with(['goal.group', 'goal.color'])
            ->active()
            ->get();

        return CardResource::collection($cards);
    }

    public function indexGroups(): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $groups = $user->groupsAsOwner()->get();

        return GroupResource::collection($groups);
    }
}
