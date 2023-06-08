<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\UpdateUser;
use App\Exceptions\ApplicationException;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserAction implements UpdateUser
{
    /**
     * @throws ApplicationException
     */
    public function execute(User $user, array $data): void
    {
        try {
            $user->fill($data);

            if ($user->isDirty('email')) {
                Log::info('[EMAIL]', [
                    'user_id' => $user->getKey(),
                    'old_email' => $user->getOriginal('email'),
                    'new_email' => $user->getAttribute('email'),
                ]);

                $user->email_verified_at = null;
            }

            $user->save();
        } catch (Throwable $e) {
            throw new ApplicationException($e);
        }
    }
}
