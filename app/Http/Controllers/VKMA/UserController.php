<?php

namespace App\Http\Controllers\VKMA;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
}
