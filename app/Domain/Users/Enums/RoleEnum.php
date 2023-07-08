<?php

namespace App\Domain\Users\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case CUSTOMER = 'Customer';
}
