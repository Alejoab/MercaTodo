<?php

namespace App\Enums;

enum ExportImportStatus: string
{
    case PENDING = 'Pending';
    case COMPLETED = 'Completed';
    case FAILED = 'Failed';
}
