<?php

namespace App\Http\Controllers\VKMA;

use App\Goal;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\GoalResource;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\ResponseFactory;

class GoalController extends Controller
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

    public function index(Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $filter = $request->query('filter', []);

        $goals = Goal::query()
            ->with(['group']);

        if ($categoryId = $filter['category_id'] ?? null) {
            $goals = $goals->whereHas('group', static function (Builder $q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }

        /** @var Paginator $goals */
        $goals = $goals->jsonApiPaginate();

        $fetchedGoals = (new Collection($goals->items()))->map(function (Goal $goal) {
            return $goal->id;
        });

        $myCards = $user->cards()
            ->whereIn('goal_id', $fetchedGoals)
            ->get();

        $myCardGoalIds = $myCards->pluck('goal_id');

        foreach ($goals->items() as $goal) {
            $goal->setAttribute('is_attached', $myCardGoalIds->contains($goal->id));
        }

        return GoalResource::collection($goals);
    }

    public function attach(int $id): CardResource
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $goal = Goal::query()->findOrFail($id);

        $card = $goal->cards()->firstOrCreate([
            'completed_at' => null,
            'user_id' => $user->id
        ]);

        $card->load(['goal.group', 'goal.color']);

        return new CardResource($card);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return GoalResource
     * @throws ValidationException
     */
    public function update(int $id, Request $request): GoalResource
    {
        $data = $this->validate($request, [
            'color_id' => 'nullable|integer|exists:colors,id',
            'checkins_count' => 'nullable|integer|min:1|max:999',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id'
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $goal = Goal::query()->findOrFail($id);
        $group = $goal->group;

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $goal->update($data);

        // todo shit
        if ($request->has('category_id')) {
            $group->category_id = $request->input('category_id');
            $group->save();
        }

        return new GoalResource($goal);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return Response|ResponseFactory
     * @throws Exception
     */
    public function destroy(int $id, Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $goal = Goal::query()->findOrFail($id);
        $group = $goal->group;

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $goal->delete();

        return response(null, 204);
    }
}
