<?php

namespace App\Services\Products;

use Illuminate\Support\Facades\File;

class ProductImagesService
{
    public function storeImage($image): string
    {
        $file_name = time().'.'.$image->extension();
        $image->move(storage_path('app/public/product_images'), $file_name);

        return $file_name;
    }

    public function deleteImage($image_path): void
    {
        File::delete(storage_path('app/public/product_images/'.$image_path));
    }
}
