<?php

namespace LocalheroPortal\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LLI\Location;
use LocalheroPortal\Models\LLI\LocationCategoryType;


/**
 *
 * @property int $id
 * @property string $name
 * @property string $gcid
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @method static Builder|GoogleBusinessCategory whereId($value)
 * @method static Builder|GoogleBusinessCategory whereName($value)
 * @method static Builder|GoogleBusinessCategory whereGcid($value)
 * @method static Builder|GoogleBusinessCategory whereCreatedAt($value)
 * @method static Builder|GoogleBusinessCategory whereUpdatedAt($value)
 * @method static Builder|GoogleBusinessCategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|GoogleBusinessCategory withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GoogleBusinessCategory withoutTrashed()
 * @mixin Eloquent
 */
class GoogleBusinessCategory extends Model
{

    use SoftDeletes;

    protected $fillable = ['id', 'gcid', 'name'];

    public function locations() : BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withPivot('type');
    }

    public function mainTypeLocations(): BelongsToMany
    {
        return $this->locations()->wherePivot('type', LocationCategoryType::MAIN);
    }

    public function additionalTypeLocations(): BelongsToMany
    {
        return $this->locations()->wherePivot('type', LocationCategoryType::ADDITIONAL);
    }

}
