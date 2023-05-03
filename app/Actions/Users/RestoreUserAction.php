<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\RestoreUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RestoreUserAction implements RestoreUser
{

    public function execute(int $id): void
    {
        // TODO: Call undefined method restore
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        Log::warning('[RESTORE]', [
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'user_id' => $id,
        ]);
    }
}
