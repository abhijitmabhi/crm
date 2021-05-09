<?php

namespace LocalheroPortal\Models\User;

use Eloquent;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jenssegers\Optimus\Optimus;
use LocalheroPortal\Core\Traits\HasOptionsField;
use LocalheroPortal\Core\Traits\ParsesNotifications;
use LocalheroPortal\Models\CalendarEvent;
use LocalheroPortal\Models\Callagent;
use LocalheroPortal\Models\Comment;
use LocalheroPortal\Models\ExpertSettings;
use LocalheroPortal\Models\Lead;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\Sales\Sale;


/**
 * LocalheroPortal\Models\User\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string|null $avatar
 * @property string $password
 * @property string|null $api_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property bool $is_active
 * @property bool $block_login
 * @property object $options
 * @property-read Collection|Callagent[] $callagents
 * @property-read int|null $callagents_count
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read Company $company
 * @property-read Collection|Lead[] $intervals
 * @property-read int|null $intervals_count
 * @property-read Collection|Lead[] $leads
 * @property-read int|null $leads_count
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Role[] $roles
 * @property-read int|null $roles_count
 * @property-read Collection|Sale[] $sales
 * @property-read int|null $sales_count
 * @method static Builder|User byCallagent(User $agent)
 * @method static Builder|User byRole($rolename)
 * @method static Builder|User byNotRole($rolename)
 * @method static bool|null forceDelete()
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static Builder|User query()
 * @method static bool|null restore()
 * @method static Builder|User whereApiToken($value)
 * @method static Builder|User whereAvatar($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeletedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereOptions($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin Eloquent
 * @method static Builder|User search($searchterm)
 * @property-read Collection|CalendarEvent[] $attendingEvents
 * @property-read int|null $attending_events_count
 * @property-read Collection|CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read Collection|CalendarEvent[] $invitedToEvents
 * @property-read int|null $invited_to_events_count
 * @property-read Collection|CalendarEvent[] $ownedEvents
 * @property-read int|null $owned_events_count
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasOptionsField;
    use ParsesNotifications;
    use HasFactory;
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'options'           => 'object',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['calendar_url'];

    /**
     * @param CalendarEvent $calendarEvent
     */
    public function attendEvent(CalendarEvent $calendarEvent)
    {
        $this->calendarEvents()->updateExistingPivot($calendarEvent->id, ["role" => "atendee"]);
    }

    /**
     * @return mixed
     */
    public function attendingEvents()
    {
        return $this->morphToMany(CalendarEvent::class, 'eventable')->withPivotValue('role', 'atendee');
    }

    public function ownedEvents()
    {
        return $this->morphToMany(CalendarEvent::class, 'eventable')->withPivotValue('role', 'owner');
    }

    /**
     * @return mixed
     */
    public function calendarEvents()
    {
        return $this->morphToMany(CalendarEvent::class, 'eventable')->withPivot('role');
    }

    /**
     * @return HasMany [description]
     */
    public function callagents(): HasMany
    {
        return $this->hasMany(Callagent::class, 'expert_id');
    }

    /**
     * @return HasMany [description]
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return HasOne [description]
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function expertSettings(): HasOne
    {
        return $this->hasOne(ExpertSettings::class);
    }

    public function getCalendarURLAttribute()
    {
        return url('/calendar/' . app(Optimus::class)->encode($this->id));
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return "/users/$this->id";
    }

    public function  getAllRoleNames(): array
    {
        $roles = [];
        foreach ($this->roles as $role){
            $roles[] = $role->name;
        }
        return $roles;
    }

    public function getAllPermissionNames(): array
    {
        $permissions = [];
        foreach ($this->roles as $role){
            foreach ($role->permissions as $permission){
                $permissions[] = $permission;
            }
        }
        return $permissions;
    }

    /**
     * @param string $permission [description]
     * @return bool   [description]
     */

    public function hasPermission(string $permission): bool
    {
        return Cache::tags(['user.' . $this->id, 'permissions'])->rememberForever($permission, function () use ($permission) {
            return $this->roles()->pluck('permissions')->flatten()->contains($permission);
        });
    }

    /**
     * @param string $role
     * @return bool
     */

    public function hasRole(string $role): bool
    {
        return Cache::tags(['user.' . $this->id])->rememberForever($role, function () use ($role) {
            return $this->roles()->where('name', $role)->exists();
        });
    }

    /**
     * @param string $role
     * @return bool
     */
    public function hasOnlyRole(string $role): bool
    {
        return 1 === $this->roles->count() && $this->roles()->where('name', $role)->exists();
    }

    /**
     * @return BelongsToMany [description]
     */

    public function intervals(): BelongsToMany
    {
        return $this->belongsToMany(Lead::class)->withPivot('time_spent');
    }

    /**
     * @return mixed
     */
    public function invitedToEvents()
    {
        return $this->morphToMany(CalendarEvent::class, 'eventable')->withPivotValue('role', 'invitee');
    }

    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function setNameAttribute($value)
    {
        $chunks = explode(' ', $value, 2);
        array_push($chunks, '');
        $this->attributes['first_name'] = $chunks[0];
        $this->attributes['last_name'] = $chunks[1];
    }


    public function getFormalNameAttribute()
    {
        return $this->last_name ? $this->last_name . ', ' . $this->first_name : $this->first_name;
    }

    /**
     * @return HasMany [description]
     */

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class, 'expert_id');
    }

    /**
     * Roles of User
     * @return BelongsToMany Eloquent Relationship; can be used to build querys
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return HasMany
     */
    public function sales()
    {
        return $this->hasMany(Sale::class, 'expert_id');
    }

    public function scopeByCallagent(Builder $query, User $agent): Builder
    {
        return $query->whereHas('callagents', function ($query) use ($agent) {
            $query->where('agent_id', $agent->id);
        });
    }

    public function scopeByRole(Builder $query, string $rolename): Builder
    {
        return $query->whereHas('roles', function ($query) use ($rolename) {
            $query->where('name', $rolename);
        });
    }

    public function scopeByNotRole(Builder $query, string $rolename): Builder
    {
        return $query->whereDoesntHave('roles', function ($query) use ($rolename) {
            $query->where('name', $rolename);
        });
    }

    /**
     * Search through Database
     * @param Builder $query
     * @param string $searchterm
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $searchterm): Builder
    {
        return $query
            ->where("name", "like", "%$searchterm%")
            ->orWhere("email", "like", "%$searchterm%");
    }

    /**
     * @return void
     */
    public function setNewApiToken(): void
    {
        try {
            $this->api_token = Str::random(60);
            $this->save();
        } catch (Exception $e) {
            Log::error('could not refresh api token' . $e->getMessage());
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function ($user) {
            Cache::tags('user.' . $user->id)->flush();
        });
    }

}
