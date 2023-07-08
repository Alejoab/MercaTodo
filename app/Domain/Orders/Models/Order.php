<?php

namespace App\Domain\Orders\Models;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\QueryBuilders\OrderQueryBuilder;
use App\Enums\PaymentMethod;
use Carbon\Carbon;
use Database\Factories\CityFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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
 * @property boolean       $active
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
            'active',
        ];

    protected $casts
        = [
            'status' => OrderStatus::class,
            'payment_method' => PaymentMethod::class,
            'active' => 'boolean',
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
