<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class ProductQueryBuilder extends Builder
{
    /**
     * Searches for products that contain the given search string in the given columns.
     *
     * @param string|null $search
     * @param array       $columns
     *
     * @return self
     */
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

    /**
     * Filters products by the given brand IDs.
     *
     * @param array|null $brandIds
     *
     * @return self
     */
    public function filterBrand(?array $brandIds): self
    {
        return $this->when(count($brandIds ?: []), function ($query) use ($brandIds) {
            $query->whereIn('products.brand_id', $brandIds);
        });
    }

    /**
     * Filters products by the given category ID.
     *
     * @param int|null $CategoryId
     *
     * @return self
     */
    public function filterCategory(?int $CategoryId): self
    {
        return $this->when($CategoryId, function ($query, $CategoryID) {
            $query->where('products.category_id', '=', $CategoryID);
        });
    }
}
