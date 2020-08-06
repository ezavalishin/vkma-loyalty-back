<?php

namespace App;

use App\Events\UserCreated;
use Eloquent;
use Exception;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Laravel\Lumen\Auth\Authorizable;

/**
 * App\User
 *
 * @property int $id
 * @property int $vk_user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $avatar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereVkUserId($value)
 * @mixin Eloquent
 * @property bool $notifications_are_enabled
 * @property Carbon|null $visited_at
 * @method static Builder|User whereNotificationsAreEnabled($value)
 * @method static Builder|User whereVisitedAt($value)
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vk_user_id',
        'first_name',
        'last_name',
        'avatar',
        'notifications_are_enabled',
        'visited_at'
    ];

    protected $casts = [
        'notifications_are_enabled' => 'boolean'
    ];

    protected $dates = [
        'visited_at'
    ];

    protected $dispatchesEvents = [
        'created' => UserCreated::class
    ];

    /**
     * @param int $vkId
     * @return static
     * @throws Exception
     */
    public static function byVkId(int $vkId): self
    {
        return retry(5, static function () use ($vkId) {
            return self::query()->firstOrCreate([
                'vk_user_id' => $vkId
            ]);
        }, 100);
    }
}
