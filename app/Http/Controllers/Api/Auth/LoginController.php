<?php

namespace App\Http\Controllers\Api\Auth;

use App\Domain\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => trans('auth.failed'),
            ], 422);
        }

        $user = User::query()->where('email', $request->get('email'))->first();
        $token = $user->createToken(Str::random())->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
            ],
        ]);
    }
}
