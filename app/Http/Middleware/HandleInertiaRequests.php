<?php

namespace App\Http\Middleware;

use App\Domain\Users\Enums\RoleEnum;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'isAdmin' => $request->user()?->hasrole(RoleEnum::SUPER_ADMIN->value) || $request->user()?->hasrole(RoleEnum::ADMIN->value),
            'role' => $request->user()?->getRoleNames()->first(),
            'permissions' => array_column($request->user()?->getAllPermissions()->toArray() ?? [], 'name'),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy())->toArray(), [
                    'location' => $request->url(),
                    'query' => $request->query(),
                ]);
            },
        ]);
    }
}
