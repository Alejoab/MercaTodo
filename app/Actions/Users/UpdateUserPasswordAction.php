<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserPasswordAction implements UpdateUserPassword
{
    public function execute(User $user, string $password): void
    {
        $user->password = Hash::make($password);
        $user->save();

        Log::warning('[PASSWORD]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $user->getKey(),
        ]);
    }
}
