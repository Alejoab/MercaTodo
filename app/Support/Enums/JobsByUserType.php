<?php

namespace App\Support\Enums;

enum JobsByUserType: string
{
    case EXPORT = 'Export';
    case IMPORT = 'Import';
    case REPORT = 'Report';
}
