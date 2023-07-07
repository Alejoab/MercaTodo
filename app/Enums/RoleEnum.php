<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN = 'Admin';
    case CUSTOMER = 'Customer';
}
