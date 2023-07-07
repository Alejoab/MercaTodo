<?php

namespace App\Exports;

use App\Enums\ExportImportStatus;
use App\Models\ExportImport;
use App\Models\Product;
use App\QueryBuilders\ProductQueryBuilder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsExport implements FromQuery, WithHeadings, ShouldQueue, WithEvents
{

    private ?string $search;
    private ?int $category;
    private ?int $brand;
    private ExportImport $export;

    public function __construct(ExportImport $export, ?string $search, ?int $category, ?int $brand)
    {
        $this->export = $export;
        $this->search = $search;
        $this->category = $category;
        $this->brand = $brand;
    }

    public function headings(): array
    {
        return [
            'code',
            'name',
            'description',
            'price',
            'stock',
            'category_name',
            'brand_name',
            'status',
        ];
    }

    public function query(): Relation|\Illuminate\Database\Eloquent\Builder|ProductQueryBuilder|Builder
    {
        /**
         * @var ProductQueryBuilder $products
         */
        $products = Product::query()->withTrashed()
            ->join('brands', 'products.brand_id', '=', 'brands.id')
            ->join('categories', 'products.category_id', '=', 'categories.id');

        return $products
            ->filterCategory($this->category)
            ->filterBrand($this->brand ? [$this->brand] : null)
            ->contains($this->search, ['products.name', 'products.code'])
            ->select(
                [
                    'products.code',
                    'products.name',
                    'products.description',
                    'products.price',
                    'products.stock',
                    'categories.name as category_name',
                    'brands.name as brand_name',
                    'products.deleted_at as status',
                ]
            )
            ->orderBy('products.code', 'desc');
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function () {
                $this->export->status = ExportImportStatus::COMPLETED;
                $this->export->save();
            },
        ];
    }

    public function failed(): void
    {
        $this->export->status = ExportImportStatus::FAILED;
        $this->export->save();
    }
}
