<?php

namespace App\Domain\Products\Enums;

enum ExportImportStatus: string
{
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case FAILED = 'Failed';
}
