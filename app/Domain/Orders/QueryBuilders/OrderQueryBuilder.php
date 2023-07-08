<?php

namespace App\Domain\Orders\QueryBuilders;

use App\Domain\Orders\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class OrderQueryBuilder extends Builder
{
    /**
     * Returns the last pending order for the given user
     *
     * @param int $userId
     *
     * @return OrderQueryBuilder|Model|null
     */
    public function getLast(int $userId): null|OrderQueryBuilder|Model
    {
        return $this->where('user_id', $userId)->where('status', OrderStatus::PENDING)->latest()->first();
    }

    /**
     * Returns the orders with a given status
     *
     * @param OrderStatus $status
     *
     * @return self
     */
    public function whereStatus(OrderStatus $status): self
    {
        return $this->where('status', $status);
    }

    /**
     * Returns the active orders
     *
     * @param bool $active
     *
     * @return self
     */
    public function whereActive(bool $active = true): self
    {
        return $this->where('active', $active);
    }

    /**
     * Returns the order of a given user
     *
     * @param int $userId
     *
     * @return self
     */
    public function whereUser(int $userId): self
    {
        return $this->where('user_id', $userId);
    }
}
