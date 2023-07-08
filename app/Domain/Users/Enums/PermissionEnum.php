<?php

namespace App\Domain\Users\Enums;

enum PermissionEnum: string
{
    case CREATE = 'Create';
    case UPDATE = 'Update';
    case DELETE = 'Delete';
}
