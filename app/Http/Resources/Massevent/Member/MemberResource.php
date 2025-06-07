<?php

namespace App\Http\Resources\Massevent\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
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
            'massevent_id' => $this->massevent_id,
            'name_for_massevent' => $this->name_for_massevent,
            'phone_for_massevent' => $this->phone_for_massevent,
            'email_for_massevent' => $this->email_for_massevent,
            'system_comment' => $this->system_comment,
            'category_id' => $this->category_id,
            'ticket_plan' => $this->ticket_plan,
            'ticket_fact' => $this->ticket_fact,
            'status' => $this->status,
            'ticket_url' => $this->ticket_url,
            'created_at' => $this->created_at,
            'notes_quantity' => $this->notesQuantity()
        ];
    }
}
