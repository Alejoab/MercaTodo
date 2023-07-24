<?php

namespace App\Domain\Products\Models;

use Database\Factories\BrandFactory;
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
class Brand extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name',
        ];

    protected static function newFactory(): Factory
    {
        return BrandFactory::new();
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
