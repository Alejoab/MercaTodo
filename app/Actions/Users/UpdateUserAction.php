<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateUserAction implements UpdateUser
{
    public function execute(User $user, array $data): void
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            Log::info('[EMAIL]', [
                'user_id' => $user->id,
                'old_email' => $user->getOriginal('email'),
                'new_email' => $user->email,
            ]);

            $user->email_verified_at = null;
        }

        $user->save();
    }
}
