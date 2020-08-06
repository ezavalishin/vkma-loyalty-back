<?php

namespace App\Http\Controllers\VKMA;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
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
        $categories = Cache::remember('categories', Carbon::now()->addMinutes(15), static function () {
            return Category::all();
        });

        return CategoryResource::collection($categories);
    }
}
