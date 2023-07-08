<?php

namespace App\Domain\Customers\Models;

use App\Domain\Customers\Enums\DocumentType;
use App\Models\User;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int          $id
 * @property string       $name
 * @property string       $surname
 * @property DocumentType $document_type
 * @property string       $document
 * @property ?string      $phone
 * @property string       $address
 * @property int          $city_id
 * @property int          $user_id
 *
 * @property City         $city
 * @property User         $user
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
            'surname',
            'document',
            'document_type',
            'phone',
            'address',
            'city_id',
            'user_id',
        ];

    protected $casts
        = [
            'document_type' => DocumentType::class,
        ];

    protected static function newFactory(): Factory
    {
        return CustomerFactory::new();
    }

    /**
     * Defines the relationship with City
     *
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    /**
     * Defines the relationship with User
     *
     * @return BelongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
