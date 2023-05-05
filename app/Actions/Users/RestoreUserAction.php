<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\RestoreUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RestoreUserAction implements RestoreUser
{
    public function execute(User $user): void
    {
        $user->restore();

        Log::warning('[RESTORE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $user->id,
        ]);
    }
}
