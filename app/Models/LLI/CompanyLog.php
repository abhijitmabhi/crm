<?php

namespace LocalheroPortal\Models\LLI;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * LocalheroPortal\Models\LLI\CompanyLog
 *
 * @property int $id
 * @property int $company_id
 * @property string|null $event
 * @property mixed $filename
 * @property string|null $message
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Company $company
 * @method static Builder|CompanyLog newModelQuery()
 * @method static Builder|CompanyLog newQuery()
 * @method static Builder|CompanyLog query()
 * @method static Builder|CompanyLog whereCompanyId($value)
 * @method static Builder|CompanyLog whereCreatedAt($value)
 * @method static Builder|CompanyLog whereEvent($value)
 * @method static Builder|CompanyLog whereFilename($value)
 * @method static Builder|CompanyLog whereId($value)
 * @method static Builder|CompanyLog whereMessage($value)
 * @method static Builder|CompanyLog whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CompanyLog extends Model
{

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

    /**
     * @param  mixed   $filename
     * @return mixed
     */
    public function getFilenameAttribute($filename)
    {
        $filename = Str::after($filename, 'company_log/');
        $this->filename = $filename;
        $this->save();
        return $filename;
    }
}
