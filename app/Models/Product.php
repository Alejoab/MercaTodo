<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable
        = [
            'code',
            'name',
            'description',
            'price',
            'stock',
            'image_path',
            'category_id',
            'brand_id',
        ];

    public function category(): belongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): belongsTo
    {
        return $this->belongsTo(Brand::class);
    }
}
