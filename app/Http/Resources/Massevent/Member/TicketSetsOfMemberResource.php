<?php

namespace App\Http\Resources\Massevent\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketSetsOfMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name_for_massevent' => $this->name_for_massevent,
            'ticket_plan' => $this->ticket_plan,
            'ticket_fact' => $this->ticket_fact,
        ];
    }
}
