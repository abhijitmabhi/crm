<?php

namespace LocalheroPortal\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LocalheroPortal\Models\User\User;

/**
 * Used to store which callagent is assigned to which expert
 *
 * @property int $id
 * @property int $agent_id
 * @property int $expert_id
 * @property-read string $name
 * @property-read User $user
 * @method static Builder|Callagent newModelQuery()
 * @method static Builder|Callagent newQuery()
 * @method static Builder|Callagent query()
 * @method static Builder|Callagent whereAgentId($value)
 * @method static Builder|Callagent whereExpertId($value)
 * @method static Builder|Callagent whereId($value)
 * @mixin \Eloquent
 * @property-read User $agent
 */
class Callagent extends Model
{
    /**
     * @var mixed
     */
    public $timestamps = false;

    protected $with = ['agent'];

    /**
     * @return mixed
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * @return string
     */
    public function getNameAttribute(): string
    {
        return $this->agent->name;
    }

    /**
     * User Relationship
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
