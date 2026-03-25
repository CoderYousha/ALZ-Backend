<?php

namespace App\Http\Services\Product;

use App\Http\DTO\Product\ProductData;
use App\Http\Services\File\FileManagementServicesClass;
use App\Models\Product;
use App\Models\ProductImage;

class ProductImageServices
{

    /**
     * @param Product $product
     * @param ProductData $productData
     */
    public function process(Product $product, ProductData $productData)
    {
        self::processOldImages($product, $productData->old_images_ids);
        $imagesData = self::processNewImages($productData->images);
        $product->images()->createMany($imagesData);
    }


    /**
     * @param Product $product
     * @param array $images
     */
    public function addNewImages(Product $product, $images)
    {
        $imagesData = self::processNewImages($images);
        $product->images()->createMany($imagesData);
    }

    /**
     * @param ProductImage $images
     */
    public function deleteImage(ProductImage $image)
    {
        if(file_exists($image->path)){
            unlink($image->path);
        }
        $image->delete();
    }


    /**
     * @param array $images array of uploaded images from request
     * @return array<array<string>>
     */
    public static function processNewImages($images){
        $imagesUrls = [];
        if(isset($images) && is_array($images))
            foreach($images as $image){
                $imagesUrls[] = [
                    'path' => FileManagementServicesClass::storeFiles($image, 'product-images')
                ];
            }

        return $imagesUrls;
    }


    /**
     * @param Product $product
     */
    public static function processOldImages(Product $product, $old_images_ids){
        if($old_images_ids){
            $product->images()->whereNotIn('id', $old_images_ids)->delete();
        }
    }


}
