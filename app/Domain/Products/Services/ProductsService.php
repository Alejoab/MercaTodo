<?php

namespace App\Domain\Products\Services;

use App\Domain\Products\Models\Product;
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
        return Product::query()
            ->withTrashed()
            ->with(['category', 'brand'])
            ->filterCategory($category)
            ->filterBrand($brand ? [$brand] : null)
            ->searchWith($search, ['product.name', 'product.code'])
            ->orderBy('products.id')
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

        return Product::query()
            ->filterCategory($category)
            ->filterBrand($brands)
            ->searchWith($search, ['product.name', 'product.code', 'brand.name', 'category.name'])
            ->orderBy($sorts[$sort][0], $sorts[$sort][1])
            ->paginate(10);
    }
}
