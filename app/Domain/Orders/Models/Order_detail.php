<?php

namespace App\Domain\Orders\Models;

use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use App\Domain\Products\Models\Product;
use Database\Factories\OrderDetailFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int      $id
 * @property int      $order_id
 * @property ?int     $product_id
 * @property string   $product_code
 * @property string   $product_name
 * @property int      $quantity
 * @property float    $amount
 * @property float    $subtotal
 *
 * @property Order    $order
 * @property ?Product $product
 *
 * @method static OrderDetailQueryBuilder query()
 */
class Order_detail extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'order_id',
            'product_id',
            'product_code',
            'product_name',
            'quantity',
            'amount',
            'subtotal',
        ];

    protected static function newFactory(): Factory
    {
        return OrderDetailFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        /**
         * @phpstan-ignore-next-line
         */
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function newEloquentBuilder($query): OrderDetailQueryBuilder
    {
        return new OrderDetailQueryBuilder($query);
    }
}
