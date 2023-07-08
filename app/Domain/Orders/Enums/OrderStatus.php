<?php

namespace App\Domain\Orders\Enums;

enum OrderStatus: string
{
    case ACCEPTED = 'Accepted';
    case REJECTED = 'Rejected';
    case PENDING = 'Pending';
}
