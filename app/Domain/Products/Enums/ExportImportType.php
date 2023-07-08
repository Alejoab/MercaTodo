<?php

namespace App\Domain\Products\Enums;

enum ExportImportType: string
{
    case EXPORT = 'Export';
    case IMPORT = 'Import';
}
