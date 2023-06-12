<?php

namespace App\Enums;

enum OrderStatus: string
{
    case ACCEPTED = 'Accepted';
    case REJECTED = 'Rejected';
    case PENDING = 'Pending';
}
