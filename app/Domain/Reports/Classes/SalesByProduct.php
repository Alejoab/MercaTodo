<?php

namespace App\Domain\Reports\Classes;

use App\Domain\Orders\Models\Order_detail;
use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use App\Domain\Reports\Enums\ReportType;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesByProduct extends BaseReport
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
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->groupBy('products.code', 'products.name', 'categories.name', 'brands.name')
            ->select([
                'products.code',
                'products.name',
                'categories.name as category',
                'brands.name as brand',
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
            'PRODUCT CODE',
            'PRODUCT NAME',
            'CATEGORY',
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
        return ReportType::R3->value;
    }
}
