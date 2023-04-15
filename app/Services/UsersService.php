<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UsersService
{
    public function store(string $email, string $password): User
    {
        $user = User::create([
            'email' => $email,
            'password' => $password,
        ]);

        $user->assignRole('Customer');

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }
}
