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

    public function filterBrand(?array $brandIds): self
    {
        return $this->when($brandIds, function ($query, $brandIds) {
            $query->where('products.brand_id', $brandIds);
        });
    }

    public function filterCategory(?int $CategoryId): self
    {
        return $this->when($CategoryId, function ($query, $CategoryID) {
            $query->where('products.category_id', '=', $CategoryID);
        });
    }
}
