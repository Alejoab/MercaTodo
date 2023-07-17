<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\RestoreUser;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Log;

class RestoreUserAction implements RestoreUser
{
    public function execute(User $user): void
    {
        $user->restore();

        Log::warning('[RESTORE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $user->getKey(),
        ]);
    }
}
