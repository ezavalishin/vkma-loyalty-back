<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Znck\Eloquent\Traits\BelongsToThrough;


/**
 * App\Goal
 *
 * @property int $id
 * @property int $color_id
 * @property int $group_id
 * @property int $checkins_count
 * @property string $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Goal newModelQuery()
 * @method static Builder|Goal newQuery()
 * @method static Builder|Goal query()
 * @method static Builder|Goal whereCheckinsCount($value)
 * @method static Builder|Goal whereColorId($value)
 * @method static Builder|Goal whereCreatedAt($value)
 * @method static Builder|Goal whereDescription($value)
 * @method static Builder|Goal whereGroupId($value)
 * @method static Builder|Goal whereId($value)
 * @method static Builder|Goal whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|Card[] $cards
 * @property-read int|null $cards_count
 * @property-read Group $group
 * @property-read Color $color
 */
class Goal extends Model
{
    use BelongsToThrough;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id',
        'checkins_count',
        'description',
        'color_id',
    ];

    protected $casts = [
        'checkins_count' => 'integer'
    ];

    protected $with = [
        'color',
        'group',
        'category'
    ];


    public function category(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Category::class, Group::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function getWsRoomKey(): string
    {
        return 'goal.' . $this->id;
    }
}
