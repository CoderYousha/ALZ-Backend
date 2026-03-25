<?php

namespace App\Http\Services\Product;

use App\Http\DTO\Product\ProductData;
use App\Models\Product;

class ProductService
{

    /**
     * @param ProductData $productData
     * @return Product
     */
    public function store(ProductData $productData){
        $product = Product::create($productData->all());
        (new ProductImageServices)->process($product, $productData);
        return $product;
    }

    /**
     * @param ProductData $productData
     * @param Product $product
     * @return Product
     */
    public function update(ProductData $productData, Product $product){
        $product->update($productData->all());
        (new ProductImageServices)->process($product, $productData);
        return $product;
    }


}
