<?php

namespace App\Domain\Products\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method self withTrashed(bool $withTrashed = true)
 */
class ProductQueryBuilder extends Builder
{
    public function searchWith(?string $search, array $columns): self
    {
        return $this->when($search, function ($query, $search) use ($columns) {
            $query->where(function ($query) use ($search, $columns) {
                foreach ($columns as $column) {
                    $column = explode('.', $column);

                    if ($column[0] === 'product') {
                        $query->orWhere($column[1], 'like', '%'.$search.'%');
                        continue;
                    }

                    $query->orWhereHas($column[0], function ($query) use ($column, $search) {
                        $query->where($column[1], 'like', '%'.$search.'%');
                    });
                }
            });
        });
    }

    public function searchJoin(?string $search, array $columns): self
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
        return $this->when(count($brandIds ?: []), function ($query) use ($brandIds) {
            $query->whereIn('products.brand_id', $brandIds);
        });
    }

    public function filterCategory(?int $CategoryId): self
    {
        return $this->when($CategoryId, function ($query, $CategoryID) {
            $query->where('products.category_id', '=', $CategoryID);
        });
    }
}
