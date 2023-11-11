<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'service' => $this->service,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'image' => asset('storage/' . $this->image)
        ];
        // return parent::toArray($request);
    }
}
