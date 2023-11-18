<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RatingResource extends JsonResource
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
            "rating" => $this->rating,
            "review" => $this->review,
            "created_at" => strtotime($this->created_at),
            "client" => [
                "id" => $this->client->id,
                "name" => $this->client->name,
                "image" => asset('storage/' . $this->client->image),
            ]
        ];
        // return parent::toArray($request);
    }
}
