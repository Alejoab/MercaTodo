<?php

namespace App\Support\Enums;

enum JobsByUserStatus: string
{
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case FAILED = 'Failed';
}
