<?php

namespace App\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class UserQueryBuilder extends Builder
{
    // TODO: global function?
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

    public function withRoles(): self
    {
        return $this->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id');
    }

    public function withCustomers($withCities = false): self
    {
        return $this->join('customers', 'customers.user_id', '=', 'users.id')
            ->when($withCities, function ($query) {
                $query->join('cities', 'customers.city_id', '=', 'cities.id')
                    ->join('departments', 'cities.department_id', '=', 'departments.id');
            });
    }
}
