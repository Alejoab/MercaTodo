<?php

namespace App\Imports;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Contracts\Actions\Products\CreateProduct;
use App\Contracts\Actions\Products\UpdateProduct;
use App\Models\ExportImport;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{

    private ExportImport $import;
    private CreateProduct $createAction;
    private UpdateProduct $updateAction;

    public function __construct(ExportImport $import)
    {
        $this->import = $import;
        $this->createAction = new CreateProductAction();
        $this->updateAction = new UpdateProductAction();
    }

    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            /**
             * @var ?Product $product
             */
            $product = Product::query()->where('id', '=', $row['id'])->latest()->first();

            if ($product) {
                $this->updateAction->execute($product, [
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'category_name' => $row['category_name'],
                    'brand_name' => $row['brand_name'],
                    'image' => null,
                ]);
            } else {
                $this->createAction->execute([
                    'code' => $row['code'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'category_name' => $row['category_name'],
                    'brand_name' => $row['brand_name'],
                    'image' => null,
                ]);
            }
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
