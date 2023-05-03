<?php

namespace App\Services\Products;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsService
{
    public function listProductsAdmin(string|null $search, int|null $category, int|null $brand): LengthAwarePaginator
    {
        return Product::withTrashed()
            ->join(
                'brands',
                'products.brand_id',
                '=',
                'brands.id'
            )
            ->join(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->when($category, function ($query, $category) {
                $query->where('products.category_id', '=', $category);
            })
            ->when($brand, function ($query, $brand) {
                $query->where('products.brand_id', '=', $brand);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', '%'.$search.'%')
                        ->orWhere('products.code', 'like', '%'.$search.'%');
                });
            })
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

    public function listProducts(string|null $search, int|null $category, array|null $brands, int|null $sort): LengthAwarePaginator
    {
        $sort = $sort !== null ? $sort : 2;
        $sorts = [0 => ['products.price', 'asc'], 1 => ['products.price', 'desc'], 2 => ['products.updated_at', 'desc'], 3 => ['products.updated_at', 'asc']];

        return Product::query()
            ->join(
                'brands',
                'products.brand_id',
                '=',
                'brands.id'
            )
            ->join(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )
            ->when($category, function ($query, $category) {
                $query->where('products.category_id', '=', $category);
            })
            ->when($brands, function ($query, $brands) {
                $query->whereIn('products.brand_id', $brands);
            })
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('products.name', 'like', '%'.$search.'%')
                        ->orWhere('products.code', 'like', '%'.$search.'%')
                        ->orWhere('brands.name', 'like', '%'.$search.'%')
                        ->orWhere('categories.name', 'like', '%'.$search.'%');
                });
            })
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
