<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    /**
     * Searches for a string in the given columns.
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
     * Excludes the given user.
     *
     * @param int $id
     *
     * @return self
     */
    public function withoutUser(int $id): self
    {
        return $this->where('users.id', '!=', $id);
    }
}
