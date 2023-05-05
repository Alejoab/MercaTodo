<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ForceDeleteUserAction implements ForceDeleteUser
{
    public function execute(User $user): void
    {
        $user->forceDelete();

        Log::warning('[FORCE DELETE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $user->id,
        ]);
    }
}
