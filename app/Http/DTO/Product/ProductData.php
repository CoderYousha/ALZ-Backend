<?php

namespace App\Http\DTO\Product;

use App\Http\DTO\Base\ObjectData;
use App\Models\Product;
use Illuminate\Http\Request;

final class ProductData extends ObjectData
{
    public ?int         $id = null;
    public ?int         $category_id;
    public ?string      $name_en;
    public ?string      $name_ar;
    public ?float       $price;
    public ?string      $description_en;
    public ?string      $description_ar;
    /** @var array|array<int,UploadedFile>  */
    public ?array   $images;
    /** @var array|array<int,int>  */
    public ?array   $old_images_ids;

    public static function fromRequest(Request $request): self
    {
        return new self([
            'category_id'       => $request->category_id,
            'name_en'           => $request->name_en,
            'name_ar'           => $request->name_ar,
            'price'             => $request->price,
            'description_en'    => $request->description_en,
            'description_ar'    => $request->description_ar,
            'images'            => $request->images         ?? [],
        ]);
    }

    public static function forUpdate(Request $request, Product $product): self
    {
        return new self([
            'category_id'       => $request->category_id    ?? $product->category_id,
            'name_en'           => $request->name_en        ?? $product->name_en,
            'name_ar'           => $request->name_ar        ?? $product->name_ar,
            'price'             => $request->price          ?? $product->price,
            'description_en'    => $request->description_en ?? $product->description_en,
            'description_ar'    => $request->description_ar ?? $product->description_ar,
            'images'            => $request->images         ?? [],
            'old_images_ids'    => $request->old_images_ids ?? [],
        ]);
    }



}
