<?php

namespace App\Domain\Orders\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class OrderDetailQueryBuilder extends Builder
{
    public function whereDateBetween(?Carbon $from, ?Carbon $to): self
    {
        $from = $from?->format('Y-m-d');
        $to = $to?->format('Y-m-d');

        return $this->when($from, function ($query, $from) {
            $query->whereDate('order_details.created_at', '>=', $from);
        })->when($to, function ($query, $to) {
                $query->whereDate('order_details.created_at', '<=', $to);
            });
    }
}
