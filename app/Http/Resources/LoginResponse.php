<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ServiceResourceCollection;
use App\Http\Resources\CategoryResourceCollection;

class LoginResponse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'role' => $this->role ? $this->role : null,
            'type' => $this->type ? $this->type : null,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'username' => $this->username ? $this->username : null,
            'name' => $this->name ? $this->name : null,
            'mobile_number' => $this->mobile_number ? $this->mobile_number : null,
            'phone_number' => $this->phone_number ? $this->phone_number : null,
            'location' => $this->location ? $this->location : null,
            'cr_copy' => $this->cr_copy ? asset('storage/' . $this->cr_copy) : null,
            'id_copy' => $this->id_copy ? asset('storage/' . $this->id_copy) : null,
            "services" => new ServiceResourceCollection($this->services),
            "categories" => new CategoryResourceCollection($this->categories),
            "project_images" => new ProfessionalProjectImageResourceCollection($this->professionalProjectImage()->select('id', 'professional_id', 'image')->get())
        ];
        // return parent::toArray($request);
    }
}
