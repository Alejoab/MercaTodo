<?php

namespace App\Services\Products;

use App\Models\Product;
use App\QueryBuilders\ProductQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsService
{
    /**
     * Lists products tho the administrator
     *
     * @param string|null $search
     * @param int|null    $category
     * @param int|null    $brand
     *
     * @return LengthAwarePaginator
     */
    public function listProductsAdmin(?string $search, ?int $category, ?int $brand): LengthAwarePaginator
    {
        /**
         * @var ProductQueryBuilder $products
         */
        $products = Product::query()->withTrashed()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');

        return $products
            ->filterCategory($category)
            ->filterBrand($brand ? [$brand] : null)
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
            ->paginate(10);
    }

    /**
     * Lists products tho the customer
     *
     * @param string|null $search
     * @param int|null    $category
     * @param array|null  $brands
     * @param int|null    $sort
     *
     * @return LengthAwarePaginator
     */
    public function listProducts(?string $search, ?int $category, ?array $brands, ?int $sort): LengthAwarePaginator
    {
        $sort = $sort !== null ? $sort : 2;
        $sorts = [
            0 => ['products.price', 'asc'],
            1 => ['products.price', 'desc'],
            2 => ['products.updated_at', 'desc'],
            3 => ['products.updated_at', 'asc'],
        ];

        /**
         * @var ProductQueryBuilder $products
         */
        $products = Product::query()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');

        return $products
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
            ->paginate(10);
    }
}
