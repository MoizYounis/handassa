<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            ]
        ];
        // return parent::toArray($request);
    }
}
