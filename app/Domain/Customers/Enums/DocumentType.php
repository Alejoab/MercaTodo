<?php

namespace App\Domain\Customers\Enums;

enum DocumentType: string
{
    case CC = 'CC';
    case NIT = 'NIT';
    case RUT = 'RUT';
}
