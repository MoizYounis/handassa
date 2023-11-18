<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            "notification" => $this->notification,
            "is_read" => $this->is_read == 0 ? false : true,
            "created_at" => strtotime($this->created_at),
            "sender" => [
                "id" => $this->sender->id,
                "name" => $this->sender->name,
                "image" => $this->sender->image ? asset('storage/' . $this->sender->image) : null
            ],
            "receiver" => [
                "id" => $this->receiver->id,
                "name" => $this->receiver->name,
                "image" => $this->receiver->image ? asset('storage/' . $this->receiver->image) : null
            ],
        ];
        // return parent::toArray($request);
    }
}
