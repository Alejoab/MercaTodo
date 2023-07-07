<?php

namespace App\Imports;

use App\Actions\Products\CreateProductAction;
use App\Actions\Products\UpdateProductAction;
use App\Contracts\Actions\Products\CreateProduct;
use App\Contracts\Actions\Products\UpdateProduct;
use App\Enums\ExportImportStatus;
use App\Exceptions\ApplicationException;
use App\Models\ExportImport;
use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsEmptyRows, WithEvents
{

    private ExportImport $import;
    private CreateProduct $createAction;
    private UpdateProduct $updateAction;
    private array $errors = [];

    public function __construct(ExportImport $import)
    {
        $this->import = $import;
        $this->createAction = new CreateProductAction();
        $this->updateAction = new UpdateProductAction();
    }

    /**
     * @throws ApplicationException
     */
    public function collection(Collection $collection): void
    {
        foreach ($collection as $row) {
            if (!$this->isValid($row->toArray())) {
                continue;
            }

            /**
             * @var ?Product $productFound
             */
            $productFound = Product::query()->withTrashed()->where('code', '=', $row['code'])->latest()->first();

            if ($productFound) {
                $product = $this->updateAction->execute($productFound, [
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
                $product = $this->createAction->execute([
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

            if ($row['status'] === 'Inactive') {
                $product->delete();
            }
        }
    }

    private function isValid(array $row): bool
    {
        try {
            Validator::make($row, [
                'code' => ['required', 'size:6'],
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'price' => ['required', 'numeric', 'min:0'],
                'stock' => ['required', 'integer', 'min:0'],
                'category_name' => ['required', 'string', 'max:255'],
                'brand_name' => ['required', 'string', 'max:255'],
                'status' => ['nullable', Rule::in(['Active', 'Inactive'])],
            ])->validate();

            return true;
        } catch (ValidationException $e) {
            $errors = [
                $row['code'] => $e->errors(),
            ];
            $this->errors += $errors;

            return false;
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function () {
                $this->import->refresh();
                $this->import->errors += $this->errors;
                $this->import->save();
            },
            AfterImport::class => function () {
                $this->import->status = ExportImportStatus::COMPLETED;
                $this->import->save();
            },
        ];
    }

    public function failed(): void
    {
        $this->import->status = ExportImportStatus::FAILED;
        $this->import->save();
    }
}
