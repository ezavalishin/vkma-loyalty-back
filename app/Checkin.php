<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


/**
 * App\Checkin
 *
 * @property int $id
 * @property int|null $approver_id
 * @property int $card_id
 * @property Carbon|null $approved_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Checkin newModelQuery()
 * @method static Builder|Checkin newQuery()
 * @method static Builder|Checkin query()
 * @method static Builder|Checkin whereApprovedAt($value)
 * @method static Builder|Checkin whereApproverId($value)
 * @method static Builder|Checkin whereCardId($value)
 * @method static Builder|Checkin whereCreatedAt($value)
 * @method static Builder|Checkin whereId($value)
 * @method static Builder|Checkin whereUpdatedAt($value)
 * @mixin Eloquent
 * @property string $hash
 * @method static Builder|Checkin whereHash($value)
 * @property-read Card $card
 */
class Checkin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'approver_id',
        'card_id',
        'approved_at',
    ];

    protected $dates = [
        'approved_at'
    ];

    protected static function booted()
    {
        static::creating(function (Checkin $checkin) {
            $checkin->hash = Str::random();
        });
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}
