<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    public function contains(?string $search, array $columns): self
    {
        return $this->when($search, function ($query, $search) use ($columns) {
            $query->where(function ($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'like', '%'.$search.'%');
                }
            });
        });
    }

    public function withBrands(): self
    {
        return $this->join('brands', 'products.brand_id', '=', 'brands.id');
    }

    public function filterBrand(?int $brandId): self
    {
        return $this->when($brandId, function ($query, $brandID) {
            $query->where('products.brand_id', '=', $brandID);
        });
    }

    public function withCategories(): self
    {
        return $this->join('categories', 'products.category_id', '=', 'categories.id');
    }

    public function filterCategory(?int $CategoryId): self
    {
        return $this->when($CategoryId, function ($query, $CategoryID) {
            $query->where('products.category_id', '=', $CategoryID);
        });
    }
}
