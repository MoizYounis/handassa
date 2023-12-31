<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
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
            'id' => $this->id ? $this->id : null,
            'role' => $this->role ? $this->role : null,
            'type' => $this->type ? $this->type : null,
            'rating' => $this->role == Constant::CLIENT ? $this->overall_client_rating : $this->overall_professional_rating,
            'experience' => $this->experience ? $this->experience : null,
            'total_project' => $this->total_project ? $this->total_project : null,
            'project_done_by_app' => $this->project_done_by_app ? $this->project_done_by_app : null,
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
