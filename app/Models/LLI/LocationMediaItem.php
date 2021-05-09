<?php
namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * LocalheroPortal\Models\LLI\LocationMediaItem
 *
 * @property int $id
 * @property string $filename
 * @property string|null $google_name
 * @property string $location_association
 * @property int $location_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $thumbnail_path
 * @property string $hash
 * @property-read Location $location
 * @method static Builder|LocationMediaItem newModelQuery()
 * @method static Builder|LocationMediaItem newQuery()
 * @method static Builder|LocationMediaItem query()
 * @method static Builder|LocationMediaItem whereCreatedAt($value)
 * @method static Builder|LocationMediaItem whereFilename($value)
 * @method static Builder|LocationMediaItem whereGoogleName($value)
 * @method static Builder|LocationMediaItem whereHash($value)
 * @method static Builder|LocationMediaItem whereId($value)
 * @method static Builder|LocationMediaItem whereLocationAssociation($value)
 * @method static Builder|LocationMediaItem whereLocationId($value)
 * @method static Builder|LocationMediaItem whereThumbnailPath($value)
 * @method static Builder|LocationMediaItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class LocationMediaItem extends Model
{
    use SoftDeletes;
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return BelongsTo
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
