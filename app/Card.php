<?php

namespace App;

use App\Events\CardCreated;
use App\Events\CheckinApproved;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;


/**
 * App\Card
 *
 * @property int $id
 * @property int $user_id
 * @property int $goal_id
 * @property bool $is_completed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Card newModelQuery()
 * @method static Builder|Card newQuery()
 * @method static Builder|Card query()
 * @method static Builder|Card whereCreatedAt($value)
 * @method static Builder|Card whereGoalId($value)
 * @method static Builder|Card whereId($value)
 * @method static Builder|Card whereIsCompleted($value)
 * @method static Builder|Card whereUpdatedAt($value)
 * @method static Builder|Card whereUserId($value)
 * @mixin Eloquent
 * @property Carbon|null $completed_at
 * @method static Builder|Card active()
 * @method static Builder|Card whereCompletedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Checkin[] $checkins
 * @property-read int|null $checkins_count
 * @property-read \App\Goal $goal
 */
class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'goal_id',
        'user_id',
    ];

    protected $dates = [
        'completed_at'
    ];

    protected $dispatchesEvents = [
        'created' => CardCreated::class
    ];

    protected $with = [
        'checkins'
    ];

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(Checkin::class);
    }

    public function scopeActive(Builder $b): Builder
    {
        return $b->whereNull('completed_at');
    }

    public function checkinBy(User $user, string $hash): void
    {
        /** @var Checkin $checkin */
        $checkin = $this->checkins()
            ->where('hash', $hash)
            ->first();

        if (!$checkin) {
            abort(403, 'hash invalid');
        }

        $checkin->update([
            'approved_at' => Carbon::now(),
            'approver_id' => $user->id
        ]);

        event(new CheckinApproved($checkin));
    }

    public function getCheckinHash(): ?string
    {
        /** @var Checkin $checkin */
        $checkin = $this->checkins
            ->filter(static function (Checkin $checkin) {
                return !$checkin->approved_at;
            })
            ->sortBy('id')
            ->first();

        if (!$checkin) {
            return null;
        }

        return $checkin->hash;
    }

    public function getTotalCheckinCount()
    {
        return $this->checkins->count();
    }

    public function getApprovedCheckinCount()
    {
        return $this->checkins->filter(static function (Checkin $checkin) {
            return $checkin->approved_at;
        })->count();
    }

    public function recreate(): Card
    {
        $this->completed_at = Carbon::now();
        $this->save();

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return self::query()->create([
            'goal_id' => $this->goal_id,
            'user_id' => $this->user_id
        ]);
    }

    public function getGroup(): ?Group
    {
        if (!$this->relationLoaded('goal')) {
            return null;
        }
        return $this->goal->group;
    }

    public function getColor(): ?Color
    {
        if (!$this->relationLoaded('goal')) {
            return null;
        }
        return $this->goal->color;
    }

    public function getDescription(): string
    {
        return $this->goal->description;
    }
}
