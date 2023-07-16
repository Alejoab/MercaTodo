<?php

namespace App\Domain\Products\Jobs;

use App\Domain\Products\Actions\CreateProductAction;
use App\Domain\Products\Actions\UpdateProductAction;
use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Support\Enums\JobsByUserStatus;
use App\Support\Exceptions\ApplicationException;
use App\Support\Models\JobsByUser;
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
use Maatwebsite\Excel\Events\AfterSheet;

class ProductsImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, SkipsEmptyRows, WithEvents
{

    private JobsByUser $import;
    private CreateProduct $createAction;
    private UpdateProduct $updateAction;
    private array $errors = [];

    public function __construct(JobsByUser $import)
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

            if (!isset($row['description'])) {
                $row['description'] = null;
            }

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
                'status' => ['required', Rule::in(['Active', 'Inactive'])],
            ])->validate();

            return true;
        } catch (ValidationException $e) {
            $errors = [
                $row['code'] ?? '' => $e->errors(),
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
        ];
    }

    public function failed(): void
    {
        $this->import->status = JobsByUserStatus::FAILED;
        $this->import->save();
    }
}
