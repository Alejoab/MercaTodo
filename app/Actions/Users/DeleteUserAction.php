<?php

namespace App\Actions\Users;

use App\Contracts\Actions\Users\DeleteUser;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeleteUserAction implements DeleteUser
{

    public function execute(int $id): void
    {
        $user = User::query()->findOrFail($id);

        $user->delete();

        Log::info('[DELETE]', [
            'user_id' => $user->getAttribute('id'),
        ]);
    }
}
