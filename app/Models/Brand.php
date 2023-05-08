<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int        $id
 * @property string     $name
 *
 * @property Collection $products
 */
class Brand extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
        ];

    /**
     * Get all the products for the Brand
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
