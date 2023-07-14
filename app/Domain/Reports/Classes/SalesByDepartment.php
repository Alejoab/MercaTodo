<?php

namespace App\Domain\Reports\Classes;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesByDepartment extends BaseReport
{
    private ?Carbon $from;
    private ?Carbon $to;

    public function __construct(?Carbon $from, ?Carbon $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query(): OrderDetailQueryBuilder|Relation|\Illuminate\Database\Eloquent\Builder|Builder
    {
        return Order::query()
            ->whereDateBetween($this->from, $this->to)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('customers', 'customers.user_id', '=', 'users.id')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->join('departments', 'cities.department_id', '=', 'departments.id')
            ->groupBy('departments.name')
            ->select([
                'departments.name',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(orders.total) as total_sales'),
                DB::raw('AVG(orders.total) as average_sale'),
            ]);
    }

    public function headings(): array
    {
        return [
            'DEPARTMENT',
            'SALES COUNT',
            'TOTAL SALES',
            'AVERAGE SALE',
        ];
    }

    public function title(): string
    {
        return 'Sales by Department';
    }
}
