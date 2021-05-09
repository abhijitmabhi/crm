<?php

namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\CompanyIndexConfigurator;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\CompanySearchRule;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\Mappings;
use LocalheroPortal\LLI\Relations\ThroughLead;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\ContactPerson;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\Sales\Product;
use LocalheroPortal\Models\User\User;
use ScoutElastic\Highlight;
use ScoutElastic\Searchable;

/**
 * LocalheroPortal\Models\LLI\Company
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string|null $url
 * @property string $email
 * @property string|null $logo
 * @property string|null $expires_at
 * @property string|null $service_package
 * @property string|null $phone
 * @property string|null $phone_mobile
 * @property string|null $contact_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CompanyGoogleAuth $googleAuth
 * @property-read Collection|Location[] $locations
 * @property-read int|null $locations_count
 * @property-read Collection|CompanyLog[] $logs
 * @property-read int|null $logs_count
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @property-read User $user
 * @method static Builder|Company newModelQuery()
 * @method static Builder|Company newQuery()
 * @method static Builder|Company query()
 * @method static Builder|Company search($searchterm)
 * @method static Builder|Company whereCreatedAt($value)
 * @method static Builder|Company whereEmail($value)
 * @method static Builder|Company whereExpiresAt($value)
 * @method static Builder|Company whereId($value)
 * @method static Builder|Company whereLogo($value)
 * @method static Builder|Company whereName($value)
 * @method static Builder|Company wherePhone($value)
 * @method static Builder|Company whereServicePackage($value)
 * @method static Builder|Company whereUpdatedAt($value)
 * @method static Builder|Company whereUrl($value)
 * @mixin Eloquent
 * @property int|null $lead_id
 * @property string|null $street
 * @property string|null $zip
 * @property string|null $city
 * @property-read Lead|null $lead
 * @method static Builder|Company whereCity($value)
 * @method static Builder|Company whereLeadId($value)
 * @method static Builder|Company whereStreet($value)
 * @method static Builder|Company whereZip($value)
 * @property-read Collection|CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Collection|ContactPerson[] $contacts
 * @property-read int|null $contacts_count
 * @property Highlight|null $highlight
 */
class Company extends Model
{
    use Searchable;

    protected $guarded = ['id'];
    protected $indexConfigurator = CompanyIndexConfigurator::class;
    protected $mapping = Mappings::mapping;

    public function allComments()
    {
        return new ThroughLead(Comment::query(), $this);
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'website' => $this->url,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' =>  "$this->street" . ' ' . "$this->zip" . ' ' . "$this->city"
        ];
    }

    protected $searchRules = [
        CompanySearchRule::class
    ];

    public function calendarEvents(): MorphMany
    {
        return $this->morphMany(CalendarEvent::class, 'eventable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function contacts()
    {
        return $this->morphMany(ContactPerson::class, 'contactable');
    }

    public function googleAuth(): HasOne
    {
        return $this->hasOne(CompanyGoogleAuth::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(CompanyLog::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sales', 'customer_id', 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, string $searchterm): Builder
    {
        return $query->where(function ($query) use ($searchterm) {
            $query->where('name', 'like', "%$searchterm%")
                ->orWhere('email', 'like', "%$searchterm%")
                ->orWhere('url', 'like', "%$searchterm%");
        });
    }
}
