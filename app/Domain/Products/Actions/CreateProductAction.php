<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Contracts\CreateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\ProductImagesService;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateProductAction implements CreateProduct
{
    /**
     * @throws ApplicationException
     */
    public function execute(array $data): Product
    {
        try {
            DB::beginTransaction();

            $brand = (new CreateBrandAction())->execute($data['brand_name']);
            $category = (new CreateCategoryAction())->execute($data['category_name']);

            if (isset($data['image']) && $data['image'] !== null) {
                $data['image'] = (new ProductImagesService())->storeImage($data['image']);
            }

            /**
             * @var Product $product
             */
            $product = Product::query()->create([
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'category_id' => $category->getAttribute('id'),
                'brand_id' => $brand->getAttribute('id'),
                'image' => $data['image'] ?? null,
            ]);

            Log::info('[CREATE]', [
                'product_id' => $product->getKey(),
                'product_code' => $product->getAttribute('code'),
            ]);

            DB::commit();

            return $product;
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e, $data);
        }
    }
}
