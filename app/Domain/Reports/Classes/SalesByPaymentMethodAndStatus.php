<?php

namespace App\Domain\Reports\Classes;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use App\Domain\Reports\Enums\ReportType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SalesByPaymentMethodAndStatus extends BaseReport
{
    public function query(): OrderDetailQueryBuilder|Relation|\Illuminate\Database\Eloquent\Builder|Builder
    {
        return Order::query()
            ->whereDateBetween($this->from, $this->to)
            ->groupBy('orders.payment_method', 'orders.status')
            ->select([
                'orders.payment_method',
                'orders.status',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(orders.total) as total_sales'),
                DB::raw('AVG(orders.total) as average_sale'),
            ]);
    }

    public function headings(): array
    {
        return [
            'PAYMENT METHOD',
            'STATUS',
            'SALES COUNT',
            'TOTAL SALES',
            'AVERAGE SALE',
        ];
    }

    public function title(): string
    {
        return ReportType::R4->value;
    }
}
