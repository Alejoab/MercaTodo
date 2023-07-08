<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\CreateUser;
use App\Domain\Users\Enums\RoleEnum;
use App\Domain\Users\Models\User;
use App\Exceptions\ApplicationException;
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

            $user->assignRole(RoleEnum::CUSTOMER->value);
            event(new Registered($user));

            return $user;
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'email' => $data['email'],
            ]);
        }
    }
}
