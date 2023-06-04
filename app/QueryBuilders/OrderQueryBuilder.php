<?php

namespace App\QueryBuilders;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderQueryBuilder extends Builder
{
    public function getLast(int $userId): null|OrderQueryBuilder|Model
    {
        return $this->where('user_id', $userId)->where('status', OrderStatus::PENDING)->latest()->first();
    }

    public function whereStatus(OrderStatus $status): self
    {
        return $this->where('status', $status);
    }
}
