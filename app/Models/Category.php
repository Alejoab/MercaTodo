<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'name'
        ];

    public static function getBrands($id = null): Collection|array
    {
        return Brand::query()->whereHas('products', function ($query) use ($id) {
            $query->when($id, function ($query, $id) {
                $query->withTrashed()->where('category_id', $id);
            });
        })->get(['name', 'id']);
    }

    public
    function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
