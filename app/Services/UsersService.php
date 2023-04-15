<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersService
{
    public function store(array $data): User
    {
        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole('Customer');

        event(new Registered($user));

        Auth::login($user);

        return $user;
    }

    public function update(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        $user->fill($data);

        if ($user->isDirty('email')) {
            Log::info('[EMAIL]', [
                'user_id' => $user->id,
                'old_email' => $user->getOriginal('email'),
                'new_email' => $user->email,
            ]);

            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }
}
