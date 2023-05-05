<?php

namespace App\Services\Products;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsService
{
    public function listProductsAdmin(?string $search, ?int $category, ?int $brand): LengthAwarePaginator
    {
        return Product::withTrashed()
            ->withBrands()
            ->withCategories()
            ->filterCategory($category)
            ->filterBrand($brand)
            ->contains($search, ['products.name', 'products.code'])
            ->select(
                [
                    'products.id',
                    'products.code',
                    'products.name',
                    'products.price',
                    'products.stock',
                    'brands.name as brand_name',
                    'categories.name as category_name',
                    'products.deleted_at as status',
                ]
            )
            ->orderBy('products.code', 'desc')
            ->paginate(50);
    }

    public function listProducts(?string $search, ?int $category, ?array $brands, ?int $sort): LengthAwarePaginator
    {
        $sort = $sort !== null ? $sort : 2;
        $sorts = [
            0 => ['products.price', 'asc'],
            1 => ['products.price', 'desc'],
            2 => ['products.updated_at', 'desc'],
            3 => ['products.updated_at', 'asc'],
        ];

        return Product::query()
            ->withBrands()
            ->withCategories()
            ->filterCategory($category)
            ->filterBrand($brands)
            ->contains($search, ['products.name', 'products.code', 'brands.name', 'categories.name'])
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.image',
                'categories.name as category_name',
                'brands.name as brand_name',
            )
            ->orderBy($sorts[$sort][0], $sorts[$sort][1])
            ->paginate(50);
    }
}
