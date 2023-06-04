<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\QueryBuilders\OrderQueryBuilder;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int           $id
 * @property string        $code
 * @property int           $user_id
 * @property OrderStatus   $status
 * @property float         $total
 * @property PaymentMethod $payment_method
 * @property ?int          $requestId
 * @property ?string       $processUrl
 * @property Carbon        $created_at
 * @property Carbon        $updated_at
 *
 * @property Collection    $order_detail
 *
 * @method static OrderQueryBuilder query()
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'code',
            'user_id',
            'status',
            'total',
            'payment_method',
            'requestId',
            'processUrl',
        ];

    protected $casts
        = [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
        ];

    public function order_detail(): HasMany
    {
        return $this->hasMany(Order_detail::class);
    }

    public function newEloquentBuilder($query): OrderQueryBuilder
    {
        return new OrderQueryBuilder($query);
    }
}
