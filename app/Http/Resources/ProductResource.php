<?php

namespace App\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    use PaginationResources;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'price' => $this->price,
            'description_en' => $this->description_en,
            'description_ar' => $this->description_ar,
            'category' => $this->whenLoaded('category', new CategoryResource($this->category)),
            'images' => $this->whenLoaded('images'),
        ];
    }
}
