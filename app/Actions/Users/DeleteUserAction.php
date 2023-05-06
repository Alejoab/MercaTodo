<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\DeleteUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeleteUserAction implements DeleteUser
{

    public function execute(User $user): void
    {
        $user->delete();

        Log::info('[DELETE]', [
            'user_id' => $user->getKey(),
        ]);
    }
}
