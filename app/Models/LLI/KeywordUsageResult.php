<?php

namespace LocalheroPortal\Models\LLI;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;

/**
 * LocalheroPortal\Models\LLI\SeoRankResult
 *
 * @property int $id
 * @property int $location_id
 * @property string $fetched_at
 * @property string $results
 * @property-read Location $location
 * @method static Builder|SeoRankResult newModelQuery()
 * @method static Builder|SeoRankResult newQuery()
 * @method static Builder|SeoRankResult query()
 * @method static Builder|SeoRankResult whereFetchedAt($value)
 * @method static Builder|SeoRankResult whereId($value)
 * @method static Builder|SeoRankResult whereResults($value)
 * @method static Builder|SeoRankResult whereLocationId($value)
 * @mixin Eloquent
 */

class KeywordUsageResult extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $dates = [
        'fetched_at'
    ];

    public function location() {
        return $this->belongsTo(Location::class);
    }
}
