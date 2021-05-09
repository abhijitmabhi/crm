<?php

namespace LocalheroPortal\Models\Sales;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\User\User;

/**
 * LocalheroPortal\Models\Sales\Product
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $min_price
 * @property string $payment_progression
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $customers
 * @property-read int|null $customers_count
 * @property-read Collection|Sale[] $sales
 * @property-read int|null $sales_count
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereMinPrice($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePaymentProgression($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|PaymentOption[] $paymentOptions
 * @property-read int|null $payment_options_count
 */
class Product extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @return HasManyThrough
     */
    public function customers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Sale::class, 'product_id', 'customer_id');
    }

    /**
     * @return HasMany
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'product_id');
    }

    public function paymentOptions()
    {
        return $this->belongsToMany(PaymentOption::class);
    }
}
