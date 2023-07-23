<?php

namespace App\Domain\Users\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method self withTrashed(bool $withTrashed = true)
 */
class UserQueryBuilder extends Builder
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

    public function withoutUser(int $id): self
    {
        return $this->where('users.id', '!=', $id);
    }
}
