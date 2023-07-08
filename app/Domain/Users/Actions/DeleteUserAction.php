<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\DeleteUser;
use App\Domain\Users\Models\User;
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
