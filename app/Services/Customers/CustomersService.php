<?php

namespace App\Services\Customers;

use App\Models\User;
use App\QueryBuilders\UserQueryBuilder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomersService
{
    /**
     * Lists customers to the admin
     *
     * @param string|null $search
     *
     * @return LengthAwarePaginator
     */
    public function listCustomersToTable(?string $search): LengthAwarePaginator
    {
        /**
         * @var UserQueryBuilder $users
         */
        $users = User::withTrashed()
            ->join('customers', 'customers.user_id', '=', 'users.id')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->join('departments', 'cities.department_id', '=', 'departments.id');

        return $users->withoutUser(auth()->user()->getAuthIdentifier())
            ->contains($search, ['customers.name', 'customers.document', 'customers.surname', 'users.email'])
            ->select(
                [
                    'users.id',
                    'users.email',
                    'customers.name',
                    'customers.surname',
                    'customers.document_type',
                    'customers.document',
                    'customers.phone',
                    'cities.name as city',
                    'departments.name as department',
                    'customers.address',
                ]
            )
            ->orderBy('users.id')
            ->paginate(10);
    }
}
