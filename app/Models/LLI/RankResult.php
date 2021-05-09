<?php


namespace LocalheroPortal\Models\LLI;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * LocalheroPortal\Models\LLI\RankResult
 *
 * @property int $id
 * @property int $rank_query_id
 * @property string $fetched_at
 * @property array | null $results
 * @property-read RankQuery $rankQuery
 * @method static Builder|RankResult newModelQuery()
 * @method static Builder|RankResult newQuery()
 * @method static Builder|RankResult query()
 * @method static Builder|RankResult whereFetchedAt($value)
 * @method static Builder|RankResult whereId($value)
 * @method static Builder|RankResult whereResults($value)
 * @method static Builder|RankResult whereRankQueryId($value)
 * @mixin Eloquent
 */
class RankResult extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $dates = [
        'fetched_at'
    ];

    protected $casts = [
        'results' => 'array',
    ];

    public function rankQuery() {
        return $this->belongsTo(RankQuery::class);
    }
}