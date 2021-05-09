<?php


namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * LocalheroPortal\Models\LLI\RankQuery
 *
 * @property int $id
 * @property string $keyword
 * @property-read Location $location
 * @property-read Collection|RankResult[] $rankResults
 * @property-read int|null $rank_results_count
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|RankQuery newModelQuery()
 * @method static Builder|RankQuery newQuery()
 * @method static Builder|RankQuery query()
 * @method static Builder|RankQuery whereId($value)
 * @method static Builder|RankQuery whereKeyword($value)
 * @method static Builder|RankQuery whereCreatedAt($value)
 * @method static Builder|RankQuery whereUpdatedAt($value)
 * @method static Builder|RankQuery whereLocationId($value)
 * @mixin Eloquent
 */
class RankQuery extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'keyword',
        'location_id',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function rankResults()
    {
        return $this->hasMany(RankResult::class);
    }
}
