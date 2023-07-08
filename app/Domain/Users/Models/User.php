<?php

namespace App\Domain\Users\Models;

use App\Domain\Customers\Models\Customer;
use App\Domain\Orders\Models\Order;
use App\Domain\Users\QueryBuilders\UserQueryBuilder;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


/**
 * @property int      $id
 * @property string   $email
 * @property ?Carbon  $email_verified_at
 * @property string   $password
 *
 * @property Customer $customer
 *
 * @method static UserQueryBuilder query()
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use \Illuminate\Auth\MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable
        = [
            'email',
            'password',
        ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    /**
     * Defines a new query builder class
     *
     * @param $query
     *
     * @return UserQueryBuilder
     */
    public function newEloquentBuilder($query): UserQueryBuilder
    {
        return new UserQueryBuilder($query);
    }

    /**
     * Defines the user-customer relation
     *
     * @return HasOne
     */
    public function customer(): hasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function order(): HasMany
    {
        /**
         * @phpstan-ignore-next-line
         */
        return $this->hasMany(Order::class)->orderByDesc('created_at');
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getDeletedAttribute($value): string
    {
        return !$value ? 'Active' : 'Inactive';
    }
}
