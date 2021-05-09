<?php

namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use LocalheroPortal\Core\Traits\UpdatesOnDuplicateKey;

/**
 * LocalheroPortal\Models\LLI\LocationInsight
 *
 * @property int $id
 * @property int $location_id
 * @property string $type
 * @property int $value
 * @property Carbon $date
 * @property-read Location $location
 * @method static Builder|LocationInsight newModelQuery()
 * @method static Builder|LocationInsight newQuery()
 * @method static Builder|LocationInsight query()
 * @method static Builder|LocationInsight whereDate($value)
 * @method static Builder|LocationInsight whereId($value)
 * @method static Builder|LocationInsight whereLocationId($value)
 * @method static Builder|LocationInsight whereType($value)
 * @method static Builder|LocationInsight whereValue($value)
 * @mixin Eloquent
 */
class LocationInsight extends Model
{
    use UpdatesOnDuplicateKey;

    public $timestamps = FALSE;

    protected $dates = ['date'];

    public $guarded = ['id'];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
