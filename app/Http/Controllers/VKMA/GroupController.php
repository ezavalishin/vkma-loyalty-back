<?php

namespace App\Http\Controllers\VKMA;

use App\Goal;
use App\Group;
use App\Http\Controllers\Controller;
use App\Http\Resources\CardResource;
use App\Http\Resources\GoalResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResource;
use App\Services\VkClient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
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
     * @param Request $request
     * @return GroupResource
     * @throws ValidationException
     */
    public function store(Request $request): GroupResource
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $this->validate($request, [
            'category_id' => 'nullable|integer|exists:categories,id',
            'access_token' => 'required|string'
        ]);

        $accessToken = $request->input('access_token');

        $group = (new VkClient($accessToken))->getAuthGroup();

        $vkGroupId = $group['id'];

        /** @var Group $group */
        $group = Group::query()->firstOrCreate([
            'vk_group_id' => $vkGroupId
        ], [
            'category_id' => $request->input('category_id')
        ]);

        $group->addOwner($user);

        return new GroupResource($group);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return GoalResource
     * @throws ValidationException
     */
    public function storeGoal(int $id, Request $request): GoalResource
    {
        $this->validate($request, [
            'color_id' => 'required|integer|exists:colors,id',
            'checkins_count' => 'required|integer|min:1|max:999',
            'description' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id'
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

//        if ($group->goals()->count() > 0) {
//            abort(422, 'limit of goals');
//        }

        $goal = $group->goals()->create([
            'color_id' => $request->input('color_id'),
            'checkins_count' => $request->input('checkins_count'),
            'description' => $request->input('description')
        ]);

        // todo shit
        $group->category_id = $request->input('category_id');
        $group->save();

        $goal->load(['color', 'category', 'group']);

        return new GoalResource($goal);
    }

    public function indexGoals($id): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $goals = $group->goals()->get();

        return GoalResource::collection($goals);
    }

    public function indexCardsForUser($id): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $goals = $group->goals()->get();


        $cards = $goals->map(static function (Goal $goal) use ($user) {
            return $goal->cards()->firstOrCreate([
                'completed_at' => null,
                'user_id' => $user->id
            ]);
        });

        $cards->load(['goal.group', 'goal.color']);

        return CardResource::collection($cards);
    }

    public function storeCashier(int $id, Request $request): UserResource
    {
        $this->validate($request, [
            'vk_user_id' => 'required|integer',
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $cashier = User::byVkId($request->input('vk_user_id'));

        $group->addCashier($cashier);

        return new UserResource($cashier);
    }

    public function indexCashiers(int $id, Request $request): AnonymousResourceCollection
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $cashiers = $group->cashiers()->get();

        return UserResource::collection($cashiers);
    }

    public function detachCashier(int $id, Request $request): Response
    {
        $this->validate($request, [
            'user_id' => 'required|integer'
        ]);

        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $group->cashiers()->detach($request->input('user_id'));

        return new Response(null, 204);
    }

    public function destroy(int $id): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        $group = Group::query()->findOrFail($id);

        if ($user->cannot('update', $group)) {
            abort(403);
        }

        $group->delete();

        return new Response(null, 204);
    }
}
