<?php

namespace App\Domain\Products\QueryBuilders;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use Illuminate\Database\Eloquent\Builder;

class ExportImportQueryBuilder extends Builder
{
    public function fromUser(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function getExports(): self
    {
        return $this->where('type', JobsByUserType::EXPORT);
    }

    public function getImports(): self
    {
        return $this->where('type', JobsByUserType::IMPORT);
    }

    public function whereStatus(JobsByUserStatus $status): self
    {
        return $this->where('status', $status);
    }

}
