<?php

namespace LocalheroPortal\Models\Sales;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use LocalheroPortal\Models\LLI\Company;
use LocalheroPortal\Models\User\User;

/**
 * LocalheroPortal\Models\Sales\Sale
 *
 * @property int $id
 * @property int $product_id
 * @property int $customer_id
 * @property int|null $expert_id
 * @property int $payed
 * @property int $payment
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Company $customer
 * @property-read User|null $expert
 * @property-read Product $product
 * @method static Builder|Sale newModelQuery()
 * @method static Builder|Sale newQuery()
 * @method static Builder|Sale query()
 * @method static Builder|Sale whereCreatedAt($value)
 * @method static Builder|Sale whereCustomerId($value)
 * @method static Builder|Sale whereExpertId($value)
 * @method static Builder|Sale whereId($value)
 * @method static Builder|Sale wherePayed($value)
 * @method static Builder|Sale wherePayment($value)
 * @method static Builder|Sale whereProductId($value)
 * @method static Builder|Sale whereStatus($value)
 * @method static Builder|Sale whereUpdatedAt($value)
 * @mixin Eloquent
 * @property int|null $payment_option_id
 * @property int $price
 * @property-read PaymentOption|null $paymentOption
 * @method static Builder|Sale wherePaymentOptionId($value)
 * @method static Builder|Sale wherePrice($value)
 */
class Sale extends Model
{
    protected $guarded = ["id"];

    /**
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    /**
     * @return BelongsTo
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function  paymentOption()
    {
        return $this->belongsTo(PaymentOption::class);
    }
}
