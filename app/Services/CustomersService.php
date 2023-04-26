<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CustomersService
{
    public function store(array $data): Builder|Model
    {
        $service = new UsersService();

        $user = $service->store([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return Customer::query()->create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'document' => $data['document'],
            'document_type' => $data['document_type'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city_id' => $data['city_id'],
            'user_id' => $user->getAttribute('id'),
        ]);
    }

    public function update(int $id, array $data): Builder|array|Collection|Model
    {
        $service = new UsersService();
        $customer = Customer::query()->findOrFail($id);

        $service->update($customer->getAttribute('user_id'), ['email' => $data['email']]);

        $customer->fill($data);

        $customer->save();

        return $customer;
    }

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
