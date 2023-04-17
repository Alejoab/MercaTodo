<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\File;

//use Intervention\Image\Facades\Image;

class ProductsService
{
    public function update(int $id, array $data, $image): Product
    {
        $product = Product::findOrFail($id);

        $brandService = new BrandsService();
        $categoryService = new CategoriesService();

        $brand = $brandService->store($data['brand_name']);
        $category = $categoryService->store($data['category_name']);
        $data['brand_id'] = $brand->id;
        $data['category_id'] = $category->id;

        $product->fill($data);

        if ($image !== null) {
            $this->deleteImage($product->image);
            $product->image = $this->storeImage($image);
        }

        $product->save();

        return $product;
    }

    public function store(array $data, $image): Product
    {
        $brandService = new BrandsService();
        $categoryService = new CategoriesService();

        $brand = $brandService->store($data['brand_name']);
        $category = $categoryService->store($data['category_name']);

        $file_name = $image !== null ? $this->storeImage($image) : null;

        return Product::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'stock' => $data['stock'],
            'category_id' => $category->id,
            'brand_id' => $brand->id,
            'image' => $file_name,
        ]);
    }

    public function storeImage($image): string
    {
        $file_name = time() . '.' . $image->extension();
        $image->move(public_path('\product_images'), $file_name);
        return $file_name;
    }

    public function deleteImage($image_path): void
    {
        File::delete(public_path('/product_images/' . $image_path));
    }

    public function destroy(int $id): void
    {
        $product = Product::findOrFail($id);

        $product->delete();
    }

    public function restore(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);

        $product->restore();
    }

    public function forceDelete(int $id): void
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->image !== null) {
            $this->deleteImage($product->image);
        }

        $product->forceDelete();
    }
}
