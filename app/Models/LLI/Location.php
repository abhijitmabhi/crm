<?php

namespace LocalheroPortal\Models\LLI;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\GoogleBusinessCategory;

/**
 * LocalheroPortal\Models\LLI\Location
 *
 * @property int $id
 * @property string|null $name
 * @property int $company_id
 * @property int|null $lead_id
 * @property string|null $address
 * @property string|null $address_addition
 * @property string|null $postcode
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $phone
 * @property string|null $mobilephone
 * @property string|null $fax
 * @property string|null $email
 * @property string|null $short_description
 * @property string|null $description
 * @property string|null $imprint
 * @property array|null $openinghours
 * @property string|null $special_openinghours
 * @property string|null $openinghours_comment
 * @property string|null $performances
 * @property array $languages
 * @property string|null $brands
 * @property array $payment_methods
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $last_synced
 * @property string|null $google_business_key
 * @property int $category_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $website
 * @property array $states
 * @property-read Company $company
 * @property-read Collection|LocationMediaItem[] $mediaItems
 * @property-read Collection|KeywordUsageResult[] $scrapingResults
 * @property-read Collection|RankQuery[] $rankQueries
 * @property-read Collection|CitationSource[] $activeCitations
 * @property-read int|null $media_items_count
 * @property-read Collection|Comment[] $comments
 * @property-read Collection|GoogleBusinessCategory[] $mainCategories
 * @property-read Collection|GoogleBusinessCategory[] $additionalCategories
 * @method static Builder|Location newModelQuery()
 * @method static Builder|Location newQuery()
 * @method static Builder|Location query()
 * @method static Builder|Location whereAddress($value)
 * @method static Builder|Location whereAddressAddition($value)
 * @method static Builder|Location whereBrands($value)
 * @method static Builder|Location whereCategoryId($value)
 * @method static Builder|Location whereCity($value)
 * @method static Builder|Location whereCompanyId($value)
 * @method static Builder|Location whereCountry($value)
 * @method static Builder|Location whereCreatedAt($value)
 * @method static Builder|Location whereDescription($value)
 * @method static Builder|Location whereEmail($value)
 * @method static Builder|Location whereFacebookUrl($value)
 * @method static Builder|Location whereFax($value)
 * @method static Builder|Location whereGoogleBusinessKey($value)
 * @method static Builder|Location whereId($value)
 * @method static Builder|Location whereImprint($value)
 * @method static Builder|Location whereInstagramUrl($value)
 * @method static Builder|Location whereLanguages($value)
 * @method static Builder|Location whereLastSynced($value)
 * @method static Builder|Location whereMobilephone($value)
 * @method static Builder|Location whereName($value)
 * @method static Builder|Location whereOpeninghours($value)
 * @method static Builder|Location whereOpeninghoursComment($value)
 * @method static Builder|Location wherePaymentMethods($value)
 * @method static Builder|Location wherePerformances($value)
 * @method static Builder|Location wherePhone($value)
 * @method static Builder|Location wherePostcode($value)
 * @method static Builder|Location whereShortDescription($value)
 * @method static Builder|Location whereSpecialOpeninghours($value)
 * @method static Builder|Location whereState($value)
 * @method static Builder|Location whereUpdatedAt($value)
 * @method static Builder|Location whereWebsite($value)
 * @mixin Eloquent
 * @property array|null $coordinates
 * @method static Builder|Location whereCoordinates($value)
 */
class Location extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "name",
        "address",
        "address_addition",
        "city",
        "postcode",
        "country",
        "state",
        "phone",
        "mobilephone",
        "fax",
        "email",
        "website",
        "coordinates",
        "openinghours",
        "description"
    ];


    protected $hidden = ['geo_coordinates'];

    /**
     * @var array
     */
    protected $casts = [
        'photos' => 'array',
        'coordinates' => 'array',
        'states' => 'array',
        'openinghours' => 'array'
    ];

    /**
     * @var array
     */
    protected $guarded = ['id'];


    /**
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * @return HasMany
     */
    public function keywordUsageResults(): hasMany
    {
        return $this->hasMany(KeywordUsageResult::class);
    }

    /**
     * @param  mixed  $value
     * @return array
     */
    public function getLanguagesAttribute($value): array
    {
        return explode(',', $value);
    }

    /**
     * @param  mixed  $value
     * @return array
     */
    public function getPaymentMethodsAttribute($value): array
    {
        return explode(',', $value);
    }

    public function rankQueries()
    {
        return $this->hasMany(RankQuery::class);
    }

    /**
     * @return HasMany
     */
    public function mediaItems(): HasMany
    {
        return $this->hasMany(LocationMediaItem::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(GoogleBusinessCategory::class)->withPivot('type');
    }

    public function mainCategories(): BelongsToMany
    {
        return $this->categories()->wherePivot('type', LocationCategoryType::MAIN);
    }

    public function additionalCategories(): BelongsToMany
    {
        return $this->categories()->wherePivot('type', LocationCategoryType::ADDITIONAL);
    }

    public function insights()
    {
        return $this->hasMany(LocationInsight::class);
    }

    public function latestInsight()
    {
        return $this->insights()->orderBy('date', 'desc')->first() ?? new LocationInsight([
                'date' => Carbon::now()->subMonths(18),
                'value' => 'unknown',
                'type' => 'unknown'
            ]);
    }

    /**
     * @param  mixed  $value
     * @return void
     */
    public function setLanguagesAttribute($value): void
    {
        $this->attributes['languages'] = implode(',', $value);
    }

    /**
     * @param  mixed  $value
     * @return void
     */
    public function setPaymentMethodsAttribute($value): void
    {
        $this->attributes['payment_methods'] = implode(',', $value);
    }

    public function addState(string $state): void
    {
        if (!in_array($state, $this->states)) {
            $this->states = array_merge($this->states, [$state]);
        }
    }

    public function removeState(string $state): void
    {
        $position = array_search($state, $this->states);
        if ($position !== false) {
            $newStates = $this->states;
            unset($newStates[$position]);
            $this->states = $newStates;
        }
    }

    public function allCitations() : BelongsToMany
    {
        return $this->belongsToMany(CitationSource::class)->withPivot('citation_source_id', 'state', 'id')->withTimestamps();
    }

    public function activeCitations() : BelongsToMany
    {
        return $this->belongsToMany(CitationSource::class)->withPivot('citation_source_id','state', 'id')->wherePivot('deleted_at', NULL)->withTimestamps();
    }
}
