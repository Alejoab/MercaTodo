<?php

namespace App\Domain\Reports\Classes;

use App\Domain\Orders\Models\Order_detail;
use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use App\Domain\Reports\Enums\ReportType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesByBrand extends BaseReport
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
        return Order_detail::query()
            ->whereDateBetween($this->from, $this->to)
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->groupBy('brands.name')
            ->select([
                'brands.name',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(order_details.subtotal) as total_sales'),
                DB::raw('AVG(order_details.subtotal) as average_sale'),
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('AVG(order_details.quantity) as average_quantity'),
            ]);
    }

    public function headings(): array
    {
        return [
            'BRAND',
            'SALES COUNT',
            'TOTAL SALES',
            'AVERAGE SALE',
            'TOTAL QUANTITY',
            'AVERAGE QUANTITY',
        ];
    }

    public function title(): string
    {
        return ReportType::R2->value;
    }
}
