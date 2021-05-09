<?php

namespace LocalheroPortal\Models\LLI;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Eloquent;

/**
 * LocalheroPortal\Models\LLI\CitationSource
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $category
 * @property string|null $url
 * @property string|null $score
 * @property Carbon|null $created_at
 * @property Carbon|null $deleted_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Location[] $locations
 * @method static Builder|CitationSource newModelQuery()
 * @method static Builder|CitationSource newQuery()
 * @method static Builder|CitationSource query()
 * @method static Builder|CitationSource whereId($value)
 * @method static Builder|CitationSource whereName($value)
 * @method static Builder|CitationSource whereCategory($value)
 * @method static Builder|CitationSource whereUrl($value)
 * @method static Builder|CitationSource whereScore($value)
 * @method static Builder|CitationSource whereCreatedAt($value)
 * @method static Builder|CitationSource whereUpdatedAt($value)
 * @method static Builder|CitationSource whereDeletedAt($value)
 * @mixin Eloquent
 */
class  CitationSource extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    public function locations() : BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withPivot('location_id')->wherePivot('deleted_at', NULL)->withTimestamps();
    }


}
