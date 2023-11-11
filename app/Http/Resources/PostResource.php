<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
use App\Models\PostProposal;
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
            'image' => asset('storage/' . $this->image),
            "client" => [
                "id" => $this->client->id,
                "name" => $this->client->name,
                "image" => asset('storage/' . $this->client->image),
            ],
            "is_requested" => auth()->user()->role == Constant::PROFESSIONAL ? (!empty(PostProposal::where('post_id', $this->id)->where('professional_id', auth()->user()->id)->first()) ? true : false) : null
        ];
        // return parent::toArray($request);
    }
}
