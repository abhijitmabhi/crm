<?php

namespace LocalheroPortal\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * LocalheroPortal\Models\User\Role
 *
 * @property int $id
 * @property string $name
 *  * @property string $display_name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $permissions
 * @property-read Collection|User[] $users
 * @property-read int|null $users_count
 * @method static Builder|Role newModelQuery()
 * @method static Builder|Role newQuery()
 * @method static Builder|Role query()
 * @method static Builder|Role whereCreatedAt($value)
 * @method static Builder|Role whereId($value)
 * @method static Builder|Role whereName($value)
 * @method static Builder|Role wherePermissions($value)
 * @method static Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Role extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'display_name', 'permissions'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($role) {
            $role->users()->detach();
        });
    }

    /**
     * @param mixed $value
     */
    public function getPermissionsAttribute($value)
    {
        return collect(explode(',', $value));
    }

    /**
     * @param mixed $value
     */
    public function setPermissionsAttribute($value)
    {
        $this->attributes['permissions'] = implode(',', $value);
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
