<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int         $id
 * @property string      $code
 * @property int         $user_id
 * @property OrderStatus $status
 * @property float       $total
 *
 * @property Collection  $order_details
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'code',
            'user_id',
            'status',
        ];

    public function order_detail(): HasMany
    {
        return $this->hasMany(Order_detail::class);
    }
}
