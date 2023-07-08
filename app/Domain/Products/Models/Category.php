<?php

namespace App\Domain\Products\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
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
class Category extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
        ];

    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }

    /**
     * Defines the relationship between category and products.
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
