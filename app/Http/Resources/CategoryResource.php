<?php

namespace App\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'id'                => $this->id,
            'name_en'           => $this->name_en,
            'name_ar'           => $this->name_ar,
            'image'             => $this->when($this->image, $this->image_url),
            'description_en'    => $this->description_en,
            'description_ar'    => $this->description_ar,
        ];
    }
}
