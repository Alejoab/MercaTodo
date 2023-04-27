<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UpdateUserAction implements UpdateUser
{

    public function execute(int $id, array $data): void
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->fill($data);

        if ($user->isDirty('email')) {
            Log::info('[EMAIL]', [
                'user_id' => $user->getAttribute('id'),
                'old_email' => $user->getOriginal('email'),
                'new_email' => $user->getAttribute('email'),
            ]);

            $user->email_verified_at = null;
        }

        $user->save();
    }
}
