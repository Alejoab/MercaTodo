<?php

namespace App\Domain\Products\Services;

use App\Support\Exceptions\ApplicationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductImagesService
{
    /**
     * Stores an image
     *
     * @param UploadedFile $image
     *
     * @return string
     * @throws \App\Support\Exceptions\ApplicationException
     */
    public function storeImage(UploadedFile $image): string
    {
        try {
            $file_name = md5($image->getFilename().microtime());
            $file_name .= '.'.$image->extension();
            Storage::disk('product_images')->put($file_name, $image->getContent());

            return $file_name;
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'image' => $image,
            ]);
        }
    }

    /**
     * Deletes an image
     *
     * @param string $image_path
     *
     * @return void
     * @throws ApplicationException
     */
    public function deleteImage(string $image_path): void
    {
        try {
            Storage::disk('product_images')->delete($image_path);
        } catch (Throwable $e) {
            throw new ApplicationException($e, [
                'image_path' => $image_path,
            ]);
        }
    }
}
