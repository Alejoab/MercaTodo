<?php

namespace App\Services\Products;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductImagesService
{
    /**
     * Stores an image
     *
     * @param UploadedFile $image
     *
     * @return string
     */
    public function storeImage(UploadedFile $image): string
    {
        $file_name = md5($image->getFilename().microtime());
        $file_name .= '.'.$image->extension();
        Storage::disk('product_images')->put($file_name, $image->getContent());

        return $file_name;
    }

    /**
     * Deletes an image
     *
     * @param string $image_path
     *
     * @return void
     */
    public function deleteImage(string $image_path): void
    {
        Storage::disk('product_images')->delete($image_path);
    }
}
