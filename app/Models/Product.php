<?php

namespace App\Models;

use App\QueryBuilders\ProductQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 *
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
