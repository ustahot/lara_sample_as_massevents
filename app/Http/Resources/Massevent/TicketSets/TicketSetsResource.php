<?php

namespace App\Http\Resources\Massevent\TicketSets;

use App\Http\Resources\Massevent\Member\TicketSetsOfMemberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketSetsResource extends JsonResource
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
            'short_url' => $this->short_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'member' => TicketSetsOfMemberResource::make($this->member)->resolve()
        ];

    }
}
