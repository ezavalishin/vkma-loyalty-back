<?php

namespace App;

use App\Events\GroupCreated;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * App\Group
 *
 * @property int $id
 * @property int $vk_group_id
 * @property string $title
 * @property int|null $city_id
 * @property int $category_id
 * @property string|null $avatar
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $cashiers
 * @property-read int|null $cashiers_count
 * @property-read Collection|User[] $owners
 * @property-read int|null $owners_count
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereAvatar($value)
 * @method static Builder|Group whereCategoryId($value)
 * @method static Builder|Group whereCityId($value)
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereTitle($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @method static Builder|Group whereVkGroupId($value)
 * @mixin Eloquent
 * @property-read \App\City|null $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Goal[] $goals
 * @property-read int|null $goals_count
 */
class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'vk_group_id',
        'city_id',
        'category_id',
        'avatar'
    ];

    protected $dispatchesEvents = [
        'created' => GroupCreated::class
    ];

    public function owners(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'owners')->withTimestamps();
    }

    public function cashiers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'cashiers')->withTimestamps();
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(Goal::class);
    }

    public function addOwner(User $user): Group
    {
        if ($this->owners()->where('users.id', $user->id)->exists()) {
            return $this;
        }

        $this->owners()->attach($user->id);

        return $this;
    }

    public function addCashier(User $user): Group
    {
        if ($this->cashiers()->where('users.id', $user->id)->exists()) {
            return $this;
        }

        $this->cashiers()->attach($user->id);

        return $this;
    }

    public function removeCashier(User $user): Group
    {
        $this->cashiers()->detach($user->id);

        return $this;
    }
}
