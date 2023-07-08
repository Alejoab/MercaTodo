<?php

namespace App\Domain\Products\Models;

use App\Domain\Products\QueryBuilders\ProductQueryBuilder;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int      $id
 * @property string   $code
 * @property int      $category_id
 * @property int      $brand_id
 * @property string   $name
 * @property ?string  $description
 * @property string   $image
 * @property float    $price
 * @property int      $stock
 * @property ?Carbon  $deleted_at
 *
 * @property Category $category
 * @property Brand    $brand
 *
 * @method static ProductQueryBuilder query()
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable
        = [
            'code',
            'name',
            'description',
            'price',
            'stock',
            'category_id',
            'brand_id',
            'image',
        ];

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

    public function category(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): belongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getStatusAttribute($value): string
    {
        return !$value ? 'Active' : 'Inactive';
    }

    public function newEloquentBuilder($query): ProductQueryBuilder
    {
        return new ProductQueryBuilder($query);
    }
}
