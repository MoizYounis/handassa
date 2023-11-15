<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ProfessionalProjectImageResourceCollection;

class PostProposalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "post_id" => $this->post_id,
            "price" => $this->price,
            "description" => $this->description,
            "professional" => [
                "id" => $this->professional->id,
                "name" => $this->professional->name,
                "image" => asset('storage/' . $this->professional->image),
                'experience' => $this->professional->experience ? $this->professional->experience : null,
                'total_project' => $this->professional->total_project ? $this->professional->total_project : null,
                'project_done_by_app' => $this->professional->project_done_by_app ? $this->professional->project_done_by_app : null,
                "project_images" => new ProfessionalProjectImageResourceCollection($this->professional->professionalProjectImage()->select('id', 'professional_id', 'image')->get())
            ]
        ];
        // return parent::toArray($request);
    }
}
