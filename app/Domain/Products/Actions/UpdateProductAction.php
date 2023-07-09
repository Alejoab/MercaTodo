<?php

namespace App\Domain\Products\Actions;

use App\Domain\Products\Contracts\UpdateProduct;
use App\Domain\Products\Models\Product;
use App\Domain\Products\Services\ProductImagesService;
use App\Support\Exceptions\ApplicationException;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateProductAction implements UpdateProduct
{

    /**
     * @throws ApplicationException
     */
    public function execute(Product $product, array $data): Product
    {
        try {
            DB::beginTransaction();

            $brand = (new CreateBrandAction())->execute($data['brand_name']);
            $category = (new CreateCategoryAction())->execute($data['category_name']);

            $data['brand_id'] = $brand->getAttribute('id');
            $data['category_id'] = $category->getAttribute('id');

            if (isset($data['image']) && $data['image'] !== null) {
                $imageService = new ProductImagesService();

                if ($product->image !== null) {
                    $imageService->deleteImage($product->image);
                }

                $data['image'] = $imageService->storeImage($data['image']);
            } else {
                unset($data['image']);
            }

            $product->fill($data);
            $product->save();

            DB::commit();

            return $product;
        } catch (ApplicationException $e) {
            DB::rollBack();
            throw $e;
        } catch (Throwable $e) {
            DB::rollBack();
            throw new ApplicationException($e, [
                'product' => $product->toArray(),
                'data' => $data,
            ]);
        }
    }
}
