<?php
namespace LocalheroPortal\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\User;

/**
 * LocalheroPortal\Models\CalendarEvent
 *
 * @property-read Model|\Eloquent $eventable
 * @method static Builder|CalendarEvent newModelQuery()
 * @method static Builder|CalendarEvent newQuery()
 * @method static Builder|CalendarEvent query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $eventable_id
 * @property string $eventable_type
 * @property string $description
 * @property string $event_begin
 * @property string $event_end
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string[] $options
 * @method static Builder|CalendarEvent whereCreatedAt($value)
 * @method static Builder|CalendarEvent whereDescription($value)
 * @method static Builder|CalendarEvent whereEventBegin($value)
 * @method static Builder|CalendarEvent whereEventEnd($value)
 * @method static Builder|CalendarEvent whereEventableId($value)
 * @method static Builder|CalendarEvent whereEventableType($value)
 * @method static Builder|CalendarEvent whereId($value)
 * @method static Builder|CalendarEvent whereUpdatedAt($value)
 * @property string $body
 * @property-read Collection|User[] $attendees
 * @property-read int|null $attendees_count
 * @property-read Collection|Company[] $companies
 * @property-read int|null $companies_count
 * @property-read Collection|User[] $invitees
 * @property-read int|null $invitees_count
 * @property-read Collection|Lead[] $leads
 * @property-read int|null $leads_count
 * @method static Builder|CalendarEvent whereBody($value)
 * @property string $type
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|CalendarEvent whereType($value)
 */
class CalendarEvent extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $casts = [
        'event_begin' => 'datetime',
        'event_end'   => 'datetime',
        'options'     => 'array',
    ];

    /**
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'eventable');
    }

    /**
     * @return mixed
     */
    public function attendees()
    {
        return $this->morphedByMany(User::class, 'eventable')->withPivotValue('role', 'atendee');
    }

    /**
     * @return mixed
     */
    public function companies()
    {
        return $this->morphedByMany(Company::class, 'eventable')->withPivotValue('role', 'company');
    }

    /**
     * @return mixed
     */
    public function invitees()
    {
        return $this->morphedByMany(User::class, 'eventable')->withPivotValue('role', 'invitee');
    }

    /**
     * @return mixed
     */
    public function leads()
    {
        return $this->morphedByMany(Lead::class, 'eventable')->withPivotValue('role', 'lead');
    }
}
