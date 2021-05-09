<?php

namespace LocalheroPortal\Models\Sales;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * LocalheroPortal\Models\Sales\PaymentOption
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string|null $description
 * @property int $rates
 * @property string $type
 * @property-read Collection|Product[] $products
 * @property-read int|null $products_count
 * @method static Builder|PaymentOption newModelQuery()
 * @method static Builder|PaymentOption newQuery()
 * @method static Builder|PaymentOption query()
 * @method static Builder|PaymentOption whereCreatedAt($value)
 * @method static Builder|PaymentOption whereDescription($value)
 * @method static Builder|PaymentOption whereId($value)
 * @method static Builder|PaymentOption whereName($value)
 * @method static Builder|PaymentOption whereRates($value)
 * @method static Builder|PaymentOption whereType($value)
 * @method static Builder|PaymentOption whereUpdatedAt($value)
 * @mixin Eloquent
 */
class PaymentOption extends Model
{
    protected $guarded = ['id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
