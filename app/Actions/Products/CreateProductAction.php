<?php

namespace App\Actions\Products;

use App\Contracts\Actions\Products\CreateProduct;
use App\Models\Product;
use App\Services\Products\ProductImagesService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateProductAction implements CreateProduct
{
    public function execute(array $data): Builder|Model
    {
        $brandAction = new CreateBrandAction();
        $categoryAction = new CreateCategoryAction();
        $imageService = new ProductImagesService();

        $brand = $brandAction->execute($data['brand_name']);
        $category = $categoryAction->execute($data['category_name']);

        $data['image'] = $imageService->storeImage($data['image']);

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
            'admin_id' => auth()->user()->getAuthIdentifier(),
            'product_id' => $product->getKey(),
            'product_code' => $product->getAttribute('code'),
        ]);

        return $product;
    }
}
