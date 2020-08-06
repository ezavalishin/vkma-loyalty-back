<?php

namespace App\Http\Controllers\VKMA;

use App\Color;
use App\Http\Controllers\Controller;
use App\Http\Resources\ColorResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ColorController extends Controller
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

    public function index(): AnonymousResourceCollection
    {
        $colors = Cache::remember('colors', Carbon::now()->addMinutes(15), static function () {
            return Color::all();
        });

        return ColorResource::collection($colors);
    }
}
