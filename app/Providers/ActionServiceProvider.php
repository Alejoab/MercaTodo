<?php

namespace App\Providers;

use App\Actions\Customers\CreateCustomerAction;
use App\Actions\Customers\UpdateCustomerAction;
use App\Actions\Users\CreateUserAction;
use App\Actions\Users\DeleteUserAction;
use App\Actions\Users\ForceDeleteUserAction;
use App\Actions\Users\RestoreUserAction;
use App\Actions\Users\UpdateUserAction;
use App\Actions\Users\UpdateUserPasswordAction;
use App\Actions\Users\UpdateUserRoleAction;
use App\Contracts\Actions\Customers\CreateCustomer;
use App\Contracts\Actions\Customers\UpdateCustomer;
use App\Contracts\Actions\Users\CreateUser;
use App\Contracts\Actions\Users\DeleteUser;
use App\Contracts\Actions\Users\ForceDeleteUser;
use App\Contracts\Actions\Users\RestoreUser;
use App\Contracts\Actions\Users\UpdateUser;
use App\Contracts\Actions\Users\UpdateUserPassword;
use App\Contracts\Actions\Users\UpdateUserRole;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings
        = [
            CreateUser::class => CreateUserAction::class,
            UpdateUser::class => UpdateUserAction::class,
            UpdateUserPassword::class => UpdateUserPasswordAction::class,
            UpdateUserRole::class => UpdateUserRoleAction::class,
            DeleteUser::class => DeleteUserAction::class,
            RestoreUser::class => RestoreUserAction::class,
            ForceDeleteUser::class => ForceDeleteUserAction::class,

            CreateCustomer::class => CreateCustomerAction::class,
            UpdateCustomer::class => UpdateCustomerAction::class,
        ];
}
