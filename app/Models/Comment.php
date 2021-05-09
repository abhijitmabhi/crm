<?php

namespace LocalheroPortal\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\User\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * Comment Class - used to track changes made by users and to keep a record of customer interaction
 *
 * @property int $id
 * @property string $body
 * @property string $reason
 * @property int $user_id
 * @property int $lead_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $date
 * @property-read mixed $time_spent
 * @property-read Lead $lead
 * @property-read User $user
 * @method static Builder|Comment forUser(User $user)
 * @method static Builder|Comment newModelQuery()
 * @method static Builder|Comment newQuery()
 * @method static Builder|Comment query()
 * @method static Builder|Comment whereBody($value)
 * @method static Builder|Comment whereCreatedAt($value)
 * @method static Builder|Comment whereDate($value)
 * @method static Builder|Comment whereId($value)
 * @method static Builder|Comment whereLeadId($value)
 * @method static Builder|Comment whereReason($value)
 * @method static Builder|Comment whereUpdatedAt($value)
 * @method static Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 * @property-read Collection|Media[] $media
 * @property-read int|null $media_count
 * @property int $commentable_id
 * @property string $commentable_type
 * @property-read Comment $commentable
 * @method static Builder|Comment whereCommentableId($value)
 * @method static Builder|Comment whereCommentableType($value)
 */
class Comment extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'body', 'reason', 'user_id', 'commentable_type', 'commentable_id'
    ];

    /**
     * Laravel relationship
     * @return MorphTo [description]
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * [forUser description]
     * @param  Builder  $query
     * @param  User  $user
     * @return Builder
     */

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->whereHas('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        });
    }

    /**
     * Laravel relationship
     * @return BelongsTo [description]
     */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
