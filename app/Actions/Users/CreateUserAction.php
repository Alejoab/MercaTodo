<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\CreateUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateUserAction implements CreateUser
{
    public function execute(array $data): Builder|Model
    {
        $user = User::query()->create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        /** @phpstan-ignore-next-line */
        $user->assignRole('Customer');

        event(new Registered($user));

        return $user;
    }
}
