<?php

namespace App\Services\Customers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomersService
{
    public function listCustomersToTable(?string $search): LengthAwarePaginator
    {
        return User::withTrashed()
            ->withCustomers(true)
            ->withoutUser(auth()->user()->getAuthIdentifier())
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
            ->paginate(50);
    }
}
