<?php

namespace App\Http\Resources;

use App\Helpers\Constant;
use App\Models\ClientRating;
use App\Models\PostProposal;
use App\Models\ProfessionalRating;
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
            "professional" => $this->professional_id ? [
                "id" => $this->professional->id,
                "name" => $this->professional->name,
                "image" => asset('storage/' . $this->professional->image),
            ] : null,
            "is_requested" => auth()->user()->role == Constant::PROFESSIONAL ? (!empty(PostProposal::where('post_id', $this->id)->where('professional_id', auth()->user()->id)->first()) ? true : false) : null,
            "is_client_reviewed" => auth()->user()->role == Constant::CLIENT ? (!empty(ProfessionalRating::where('post_id', $this->id)->where('client_id', auth()->user()->id)->first()) ? true : false) : null,
            "is_professional_reviewed" => auth()->user()->role == Constant::PROFESSIONAL ? (!empty(ClientRating::where('post_id', $this->id)->where('professional_id', auth()->user()->id)->first()) ? true : false) : null
        ];
        // return parent::toArray($request);
    }
}
