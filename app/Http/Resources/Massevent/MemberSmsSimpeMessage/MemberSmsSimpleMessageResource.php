<?php

namespace App\Http\Resources\Massevent\MemberSmsSimpeMessage;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberSmsSimpleMessageResource extends JsonResource
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
            'member_id' => $this->member_id,
            'real_used_phone_at_sending' => $this->real_used_phone_at_sending,
            'text' => $this->text,
            'status' => $this->status,
            'sent_at' => $this->sent_at,
            'service_sent_response' => $this->service_sent_response,
            'last_status_at' => $this->last_status_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
