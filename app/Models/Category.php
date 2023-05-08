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
class Category extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
        ];


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
