<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\CreateProduct;
use App\Exceptions\ApplicationException;
use App\Models\Product;
use App\Services\Products\ProductImagesService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CreateProductAction implements CreateProduct
{
    /**
     * @throws ApplicationException
     */
    public function execute(array $data): Builder|Model
    {
        try {
            DB::beginTransaction();

            $brand = (new CreateBrandAction())->execute($data['brand_name']);
            $category = (new CreateCategoryAction())->execute($data['category_name']);

            if ($data['image']) {
                $data['image'] = (new ProductImagesService())->storeImage($data['image']);
            }

            $product = Product::query()->create([
                'code' => $data['code'],
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'],
                'category_id' => $category->getAttribute('id'),
                'brand_id' => $brand->getAttribute('id'),
                'image' => $data['image'],
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
