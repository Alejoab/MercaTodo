<?php

namespace App\Services\Customers;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomersService
{
    public function listCustomersToTable(string|null $search): LengthAwarePaginator
    {
        return User::withTrashed()
            ->join(
                'customers',
                'customers.user_id',
                '=',
                'users.id'
            )
            ->join(
                'cities',
                'customers.city_id',
                '=',
                'cities.id'
            )
            ->join(
                'departments',
                'cities.department_id',
                '=',
                'departments.id'
            )
            ->where('users.id', '!=', auth()->user()->getAuthIdentifier())
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('customers.name', 'like', '%'.$search.'%')
                        ->orWhere('customers.document', 'like', '%'.$search.'%')
                        ->orWhere('customers.surname', 'like', '%'.$search.'%')
                        ->orWhere('users.email', 'like', '%'.$search.'%');
                });
            })
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
