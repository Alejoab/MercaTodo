<?php

namespace App\Support\QueryBuilders;

use App\Support\Enums\JobsByUserStatus;
use App\Support\Enums\JobsByUserType;
use Illuminate\Database\Eloquent\Builder;

class JobsByUserQueryBuilder extends Builder
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

    public function getReports(): self
    {
        return $this->where('type', JobsByUserType::REPORT);
    }

    public function getSales(): self
    {
        return $this->where('type', JobsByUserType::SALES);
    }

    public function whereCompletedStatus(): self
    {
        return $this->where('status', JobsByUserStatus::COMPLETED)->orWhere('status', JobsByUserStatus::FAILED);
    }

}
