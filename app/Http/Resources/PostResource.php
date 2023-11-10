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
            'image' => asset('storage/' . $this->image),
            "proposals" => ($this->status == Constant::NEW && auth()->user()->role == Constant::CLIENT) ? new PostProposalResourceCollection($this->proposals()->with('professional:id,name,image')->get()) : null
        ];
        // return parent::toArray($request);
    }
}
