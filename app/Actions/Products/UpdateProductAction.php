<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\UpdateProduct;
use App\Exceptions\ApplicationException;
use App\Models\Product;
use App\Services\Products\ProductImagesService;
use Illuminate\Support\Facades\DB;
use Throwable;

class UpdateProductAction implements UpdateProduct
{

    /**
     * @throws ApplicationException
     */
    public function execute(Product $product, array $data): void
    {
        try {
            DB::beginTransaction();

            $brand = (new CreateBrandAction())->execute($data['brand_name']);
            $category = (new CreateCategoryAction())->execute($data['category_name']);

            $data['brand_id'] = $brand->getAttribute('id');
            $data['category_id'] = $category->getAttribute('id');

            if ($data['image'] !== null) {
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
