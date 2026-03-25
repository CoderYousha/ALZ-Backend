<?php

namespace App\Http\Resources;

use App\Http\Traits\PaginationResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'name' => $this->name,
            'phone_code' => $this->phone_code,
            'phone' => $this->phone,
            'language' => $this->language,
            'image' => $this->when($this->image, $this->image_url),
            'account_role' => $this->account_role,
            'is_active' => $this->is_active,
            'verified_at' => $this->verified_at,
            'login_type' => $this->login_type,
        ];
    }
}
