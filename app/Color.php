<?php

namespace App;

use Eloquent;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Color
 *
 * @property int $id
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Color whereValue($value)
 * @mixin \Eloquent
 */
class Color extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
    ];
}
