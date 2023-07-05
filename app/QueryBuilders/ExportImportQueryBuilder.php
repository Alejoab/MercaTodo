<?php

namespace App\QueryBuilders;

use App\Enums\ExportImportStatus;
use App\Enums\ExportImportType;
use Illuminate\Database\Eloquent\Builder;

class ExportImportQueryBuilder extends Builder
{
    public function fromUser(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function getExports(): self
    {
        return $this->where('type', ExportImportType::EXPORT);
    }

    public function getImports(): self
    {
        return $this->where('type', ExportImportType::IMPORT);
    }

    public function whereStatus(ExportImportStatus $status): self
    {
        return $this->where('status', $status);
    }

}
