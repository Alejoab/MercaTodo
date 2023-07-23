<?php

namespace App\Domain\Orders\QueryBuilders;

use App\Domain\Orders\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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

    public function whereActive(bool $active = true): self
    {
        return $this->where('active', $active);
    }

    public function whereUser(int $userId): self
    {
        return $this->where('user_id', $userId);
    }

    public function whereDateBetween(?Carbon $from, ?Carbon $to): self
    {
        $from = $from?->format('Y-m-d');
        $to = $to?->format('Y-m-d');

        return $this->when($from, function ($query, $from) {
            $query->whereDate('orders.created_at', '>=', $from);
        })
            ->when($to, function ($query, $to) {
                $query->whereDate('orders.created_at', '<=', $to);
            });
    }
}
