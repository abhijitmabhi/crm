<?php

namespace LocalheroPortal\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\User;

/**
 * LocalheroPortal\Models\Batch
 *
 * @property int $id
 * @property array $leads
 * @property string $items
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Batch newModelQuery()
 * @method static Builder|Batch newQuery()
 * @method static Builder|Batch query()
 * @method static Builder|Batch whereCreatedAt($value)
 * @method static Builder|Batch whereId($value)
 * @method static Builder|Batch whereLeads($value)
 * @method static Builder|Batch whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int $expert_id
 * @property-read User $expert
 * @method static Builder|Batch whereExpertId($value)
 */
class Batch extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'items' => 'array',
        'options' => 'array',
    ];

    protected $guarded = ['id'];

    public function deleteBatch()
    {
        switch ($this->type) {
            case 'lead':
                foreach (Lead::whereIn('id', $this->items)->withTrashed()->cursor() as $lead) {
                    /**
                     * @var Lead $lead
                     */
                    $lead->comments()->delete();
                    $lead->intervals()->delete();
                    $lead->forceDelete();
                }
                $this->delete();
            break;
            case 'company':
                foreach (Company::whereIn('id', $this->items)->cursor() as $company) {
                    $company->products()->delete();
                    $company->forceDelete();
                }
                $this->delete();
            break;
        }
    }

    /**
     * @return mixed
     */
    public function expert()
    {
        return $this->hasOne(User::class, 'id', 'expert_id');
    }
}
