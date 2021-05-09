<?php

namespace LocalheroPortal\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use LocalheroPortal\Callcenter\Jobs\FetchLeadCoordinates;
use LocalheroPortal\Callcenter\Jobs\PushModelToDiabolocom;
use LocalheroPortal\Callcenter\Notifications\LeadAmount;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\LeadIndexConfigurator;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\LeadSearchRule;
use LocalheroPortal\Core\Feature\Search\ElasticSearch\Mappings;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\RoleType;
use LocalheroPortal\Models\User\User;
use ScoutElastic\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Lead Class contains Adress data and Lead status Information
 *
 * @property int $id
 * @property string $company_name
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string|null $title
 * @property string $contact_name
 * @property string|null $additional_contacts
 * @property string $phone1
 * @property string|null $phone2
 * @property string|null $email
 * @property string|null $website
 * @property string $category
 * @property string $status
 * @property int $expert_status
 * @property Carbon|null $closed_until
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property int $expert_id
 * @property int|null $agent_id
 * @property array|null $coordinates
 * @property-read Collection|Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read User $expert
 * @property-read Collection|User[] $intervals
 * @property-read int|null $intervals_count
 * @method static Builder|Lead expertAccepted()
 * @method static Builder|Lead expertOpen()
 * @method static Builder|Lead expertRejected()
 * @method static Builder|Lead forUser(User $user)
 * @method static bool|null forceDelete()
 * @method static Builder|Lead newModelQuery()
 * @method static Builder|Lead newQuery()
 * @method static \Illuminate\Database\Query\Builder|Lead onlyTrashed()
 * @method static Builder|Lead query()
 * @method static Builder|Lead recall()
 * @method static bool|null restore()
 * @method static Builder|Lead search($searchterm)
 * @method static Builder|Lead stateAppointment()
 * @method static Builder|Lead stateCallable()
 * @method static Builder|Lead stateClosed()
 * @method static Builder|Lead stateOpen()
 * @method static Builder|Lead stateReopened()
 * @method static Builder|Lead whereAdditionalContacts($value)
 * @method static Builder|Lead whereAgentId($value)
 * @method static Builder|Lead whereCategory($value)
 * @method static Builder|Lead whereCity($value)
 * @method static Builder|Lead whereClosedUntil($value)
 * @method static Builder|Lead whereCompanyName($value)
 * @method static Builder|Lead whereContactName($value)
 * @method static Builder|Lead whereCoordinates($value)
 * @method static Builder|Lead whereCreatedAt($value)
 * @method static Builder|Lead whereDeletedAt($value)
 * @method static Builder|Lead whereEmail($value)
 * @method static Builder|Lead whereExpertId($value)
 * @method static Builder|Lead whereExpertStatus($value)
 * @method static Builder|Lead whereId($value)
 * @method static Builder|Lead wherePhone1($value)
 * @method static Builder|Lead wherePhone2($value)
 * @method static Builder|Lead whereStatus($value)
 * @method static Builder|Lead whereStreet($value)
 * @method static Builder|Lead whereTitle($value)
 * @method static Builder|Lead whereUpdatedAt($value)
 * @method static Builder|Lead whereWebsite($value)
 * @method static Builder|Lead whereZip($value)
 * @method static \Illuminate\Database\Query\Builder|Lead withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Lead withoutTrashed()
 * @mixin Eloquent
 * @property string|null $customer_since
 * @property int|null $blocked
 * @property-read Company $company
 * @method static Builder|Lead stateInvalid()
 * @method static Builder|Lead whereBlocked($value)
 * @method static Builder|Lead whereCustomerSince($value)
 * @property-read User|null $agent
 * @property-read Collection|CalendarEvent[] $calendarEvents
 * @property-read int|null $calendar_events_count
 * @property-read Collection|ContactPerson[] $contacts
 * @property-read int|null $contacts_count
 * @property array $states
 */
class Lead extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Searchable;

    protected $indexConfigurator = LeadIndexConfigurator::class;

    public $isAcceptance;
    public $isCorrection;

    protected $mapping = Mappings::mapping;

    protected $casts = [
        'closed_until' => 'datetime',
        'coordinates'  => 'array',
        'blocked'      => 'boolean',
        'states' => 'array',
    ];

    protected $fillable = [
        'company_name',
        'place_id',
        'street',
        'zip',
        'city',
        'title',
        'contact_name',
        'additional_contacts',
        'phone1',
        'phone2',
        'email',
        'category',
        'sub_category',
        'website',
        'status',
        'expert_status',
        'coordinates',
        'expert_id',
        'agent_id',
        'blocked',
        'important_note'
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

    public function toSearchableArray()
    {
        return [
            'name' => $this->company_name,
            'phone' => $this->phone1,
            'email' => $this->email,
            'address' => $this->street . ' ' .  $this->zip . ' ' . $this->city
       ];
    }

    public static function makeAllSearchable()
    {
        $self = new static();
        $self->newQuery()->whereHas('expert')->orderBy($self->getKeyName())->searchable();
    }

    protected $searchRules = [
        LeadSearchRule::class
    ];


    protected $isLocking = false;

    /**
     * Relation Section
     */

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function calendarEvents()
    {
        return $this->morphToMany(CalendarEvent::class, 'eventable')->withPivotValue('role', 'lead');
    }

    public function allCalendarEvents()
    {
        return $this->calendarEvents()->withTrashed();
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getCommentsNotReached(): MorphMany
    {
        return $this->comments()->where('reason', '=', CommentReason::NOT_REACHED);
    }

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function contacts()
    {
        return $this->morphMany(ContactPerson::class, 'contactable');
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function intervals(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('time_spent');
    }

    public function getApiLink()
    {
        return "/api/leads/$this->id";
    }

    /**
     * locks a Lead until the next day. Used to prevent the same Lead being shown to multiple Callagents
     * @param string $date [description]
     */
    public function lock(int $agentId, string $date = 'tomorrow 12:00', bool $save = true): void
    {
        $this->in_pipeline = false;
        $this->agent_id = $agentId;
        if ($save) {
            $this->save();
        }
    }

    public function unlock(bool $save = true): void
    {
        $this->in_pipeline = true;
        if ($save) {
            $this->save();
        }
    }

    /**
     * Query Builder Scope Section
     */

    public function scopeExpertAccepted(Builder $query)
    {
        return $query->whereExpertStatus([LeadExpertAcceptance::ACCEPTED]);
    }

    public function scopeExpertOpen(Builder $query)
    {
        return $query->whereExpertStatus([LeadExpertAcceptance::OPEN]);
    }

    public function scopeExpertRejected(Builder $query)
    {
        return $query->whereExpertStatus([LeadExpertAcceptance::REJECTED]);
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereExpertId($user->id);
    }

    public function scopeState(Builder $query, $state): Builder
    {
        return $query->where('status', '=', $state);
    }

    public function scopeNotReached(Builder $query): Builder
    {
        return $query->where('status', '=', LeadState::NOT_REACHED);
    }

    public function scopeRecall(Builder $query): Builder
    {
        return $query->where('status', '=', LeadState::RECALL);
    }

    public function scopeAcuteRecalls(Builder $query) : Builder
    {
        return $query
            ->whereDay('closed_until', '=', now('Europe/Berlin'))
            ->whereNotNull('contact_name')
            ->state(LeadState::RECALL);
    }

    public function scopeStateAppointment(Builder $query): Builder
    {
        return $query->where('status', '=', LeadState::APPOINTMENT);
    }

    public function scopeStateClosed(Builder $query): Builder
    {
        return $query
            ->where('status', '=', LeadState::CLOSED)
            ->callable();
    }

    public function scopeStateInvalid(Builder $query): Builder
    {
        return $query
            ->where('status', '=', LeadState::INVALID)
            ->callable();
    }

    public function scopeStateOpen(Builder $query): Builder
    {
        return $query
            ->whereNotNull('contact_name')
            ->where('in_pipeline', 1)
            ->where('blocked', '=', 0)
            ->where(function (Builder $query) {
                return $query
                    ->where('status', '=', LeadState::OPEN)
                    ->orWhere(function (Builder $query) {
                        return $query
                            ->whereIn('status', [LeadState::NOT_REACHED, LeadState::NO_INTEREST])
                            ->callable();
                    });
            });
    }

    public function scopeCallable(Builder $query): Builder
    {
        return $query
            ->where(function (Builder $query) {
                return $query
                    ->orWhereNull('closed_until')
                    ->orWhere('closed_until', '<', now('Europe/Berlin')->toDateTimeString());
            });
    }

    public function scopeStateReopened(Builder $query): Builder
    {
        return $query
            ->where('status', '=', LeadState::OPEN)
            ->callable();
    }

    protected static function boot()
    {
        parent::boot();
        static::updating(function (Lead $model) {
            self::record_changes($model);
            self::restore_self($model);
        });
        static::created(function (Lead $model) {
            self::fetchCoordinates($model);
            PushModelToDiabolocom::dispatch($model);
        });
    }

    /**
     * @param int $modelId
     * @param string|null $deleted_at
     * @return void
     */
    private static function blacklistComment(self $model)
    {
        $comment = new Comment();
        $comment->user_id = Auth::id();
        if (empty($model->deleted_at)) {
            $comment->reason = CommentReason::REOPEN_OLD;
            $comment->body = 'Von Blacklist entfernt';
        } else {
            $comment->reason = CommentReason::BLACKLIST;
            $comment->body = 'Zu Blacklist hinzugefügt';
        }
        $model->comments()->save($comment);
    }

    /**
     * Sends out notification to all Callcenter Supervisors if too few leads are in the Pipeline
     * @param self $model
     */
    private static function check_pipeline(self $model)
    {
        // only send notification, if model change was not the lock-action
        if (!$model->isLocking) {
            return false;
        }
        // only send Notification if status has changed
        if ($model->getOriginal('status') == $model->status) {
            return false;
        }
        $expert = $model->expert;
        $openLeads = Lead::forUser($expert)->stateOpen()->count();

        $supervisors = User::byRole(RoleType::CALL_CENTER_SUPERVISOR)->get();

        if (in_array($openLeads, [100, 50, 10])) {
            Notification::send($supervisors, new LeadAmount($openLeads, $expert));
        }
    }

    /**
     * @param self $self
     */
    private static function fetchCoordinates(self $self)
    {
        FetchLeadCoordinates::dispatch($self);
    }

    /**
     * @param self $self
     */
    private static function fetchMapScreenshot(int $leadId)
    {
        FetchLeadCoordinates::dispatch($leadId);
    }

    /**
     *
     * @param self $model
     * @return void
     */
    private static function record_changes(self $model): void
    {
        $toTrack = collect($model->getDirty())->only([
            'company_name',
            'street',
            'zip',
            'city',
            'titel',
            'contact_name',
            'additional_contacts',
            'phone1',
            'email',
            'website',
            'deleted_at',
        ]);
        $changedAttributes = [];
        $toTrack->each(function ($attribute, $key) use (&$changedAttributes, $model) {
            switch ($key) {
                case 'deleted_at':
                    self::blacklistComment($model);
                    break;
                default:
                    $changedAttributes[] = __("attributes.$key") . ' alt: ' . $model->getOriginal($key);
            }
        });
        //TODO: move to LeadController?
        if (count($changedAttributes)) {
            $comment = new Comment();
            //TODO: how can we remove that hardcoded value?
            $comment->user_id = Auth::id() ?? 1;
            $comment->reason = CommentReason::CORRECTION_OLD;
            $comment->body = implode(', ', $changedAttributes);
            $model->comments()->save($comment);
        }
    }

    /**
     * @param self $lead
     * @return void
     */
    private static function restore_self(self $lead)
    {
        if ($lead->trashed() && $lead->isDirty('status') && $lead->status != LeadState::BLACKLIST) {
            $lead->restore();
        }
    }

    /**
     * Returns known phone numbers as an array
     * @return array
     */
    public function getPhoneNrsArray()
    {
        $phoneNrs = [];
        foreach ([1, 2] as $nr) {
            $phone = "phone$nr";
            if (isset($this->{$phone})) {
                $phoneNrs[] = $this->{$phone};
            }
        }
        return $phoneNrs;
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

}
