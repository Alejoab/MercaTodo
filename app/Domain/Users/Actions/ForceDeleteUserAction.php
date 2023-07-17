<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\ForceDeleteUser;
use App\Domain\Users\Models\User;
use Illuminate\Support\Facades\Log;

class ForceDeleteUserAction implements ForceDeleteUser
{
    public function execute(User $user): void
    {
        $user->forceDelete();

        Log::warning('[FORCE DELETE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $user->getKey(),
        ]);
    }
}
