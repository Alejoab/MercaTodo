<?php

namespace App\Domain\Reports\Jobs;

use App\Domain\Orders\Models\Order_detail;
use App\Domain\Orders\QueryBuilders\OrderDetailQueryBuilder;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromQuery, WithHeadings, ShouldQueue, ShouldAutoSize, WithStyles
{
    private JobsByUser $sales;
    private ?Carbon $from;
    private ?Carbon $to;

    public function __construct(JobsByUser $sales, ?Carbon $from, ?Carbon $to)
    {
        $this->sales = $sales;
        $this->from = $from;
        $this->to = $to;
    }

    public function query(): OrderDetailQueryBuilder|Relation|\Illuminate\Database\Eloquent\Builder|Builder
    {
        return Order_detail::query()
            ->whereDateBetween($this->from, $this->to)
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('customers', 'customers.user_id', '=', 'users.id')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->join('departments', 'cities.department_id', '=', 'departments.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select([
                'orders.code as order_code',
                'orders.status as order_status',
                'orders.payment_method as order_payment_method',
                'cities.name as city_name',
                'departments.name as department_name',
                'products.code as code',
                'categories.name as category_name',
                'brands.name as brand_name',
                'order_details.quantity as quantity',
                'order_details.amount as amount',
                'order_details.subtotal as subtotal',
                'orders.created_at as order_date',
            ]);
    }

    public function headings(): array
    {
        return [
            'ORDER_CODE',
            'ORDER_STATUS',
            'ORDER_PAYMENT_METHOD',
            'CITY_NAME',
            'DEPARTMENT_NAME',
            'PRODUCT_CODE',
            'CATEGORY_NAME',
            'BRAND_NAME',
            'QUANTITY',
            'AMOUNT',
            'SUBTOTAL',
            'ORDER_DATE',
        ];
    }

    public function failed(): void
    {
        $this->sales->status = JobsByUserStatus::FAILED;
        $this->sales->save();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true,],
                'alignment' => ['horizontal' => 'center',],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '8DB4E2',],
                ],
            ],
        ];
    }
}
