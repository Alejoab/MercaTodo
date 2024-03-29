<?php

namespace App\Domain\Products\Jobs;

use App\Domain\Products\Models\Product;
use App\Domain\Products\QueryBuilders\ProductQueryBuilder;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Mails\JobsByUserMail;
use App\Support\Models\JobsByUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromQuery, WithHeadings, ShouldQueue, ShouldAutoSize, WithStyles, WithColumnWidths, WithStrictNullComparison
{

    private ?string $search;
    private ?int $category;
    private ?int $brand;
    private JobsByUser $export;

    public function __construct(JobsByUser $export, ?string $search, ?int $category, ?int $brand)
    {
        $this->export = $export;
        $this->search = $search;
        $this->category = $category;
        $this->brand = $brand;
    }

    public function headings(): array
    {
        return [
            'CODE',
            'PRICE',
            'STOCK',
            'CATEGORY_NAME',
            'BRAND_NAME',
            'STATUS',
            'NAME',
            'DESCRIPTION',
        ];
    }

    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|ProductQueryBuilder|Builder
    {
        return Product::query()
            ->withTrashed()
            ->filterCategory($this->category)
            ->filterBrand($this->brand ? [$this->brand] : null)
            ->searchJoin($this->search, ['products.name', 'products.code'])
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                [
                    'products.code',
                    'products.price',
                    'products.stock',
                    'categories.name as category_name',
                    'brands.name as brand_name',
                    'products.deleted_at as status',
                    'products.name',
                    'products.description',
                ]
            )
            ->orderBy('products.code', 'desc');
    }

    public function failed(): void
    {
        $this->export->status = JobsByUserStatus::FAILED;
        $this->export->save();

        Mail::to($this->export->user->email)->queue(new JobsByUserMail($this->export));
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            'A:H' => [
                'alignment' => ['vertical' => 'center',],
            ],
            'G:H' => [
                'alignment' => ['wrapText' => true],
            ],
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

    public function columnWidths(): array
    {
        return [
            'H' => 70,
        ];
    }
}
