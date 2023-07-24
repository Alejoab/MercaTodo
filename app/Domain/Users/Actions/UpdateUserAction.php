<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Contracts\UpdateUser;
use App\Domain\Users\Models\User;
use App\Support\Exceptions\ApplicationException;
use App\Support\Exceptions\CustomException;
use Illuminate\Support\Facades\Log;
use Throwable;

class UpdateUserAction implements UpdateUser
{
    /**
     * @throws CustomException
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
            throw new ApplicationException($e, $data);
        }
    }
}
