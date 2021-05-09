<?php

namespace LocalheroPortal\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * LocalheroPortal\Models\ContactPerson
 *
 * @property int $id
 * @property string $contactable_type
 * @property int $contactable_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Model|Eloquent $contactable
 * @method static Builder|ContactPerson newModelQuery()
 * @method static Builder|ContactPerson newQuery()
 * @method static Builder|ContactPerson query()
 * @method static Builder|ContactPerson whereContactableId($value)
 * @method static Builder|ContactPerson whereContactableType($value)
 * @method static Builder|ContactPerson whereCreatedAt($value)
 * @method static Builder|ContactPerson whereEmail($value)
 * @method static Builder|ContactPerson whereId($value)
 * @method static Builder|ContactPerson whereName($value)
 * @method static Builder|ContactPerson wherePhone($value)
 * @method static Builder|ContactPerson whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ContactPerson extends Model
{
    protected $guarded = ['id'];

    public function contactable()
    {
        return $this->morphTo();
    }
}
