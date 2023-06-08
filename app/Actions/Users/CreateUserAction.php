<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\CreateUser;
use App\Exceptions\ApplicationException;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Throwable;

class CreateUserAction implements CreateUser
{
    /**
     * @throws ApplicationException
     */
    public function execute(array $data): Builder|Model
    {
        try {
            /** @var User $user */
            $user = User::query()->create([
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('Customer');
            event(new Registered($user));

            return $user;
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
