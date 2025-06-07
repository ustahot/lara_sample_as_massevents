<?php

namespace App\Http\Resources\Massevent\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberForReportWithoutIsmailovoResource extends JsonResource
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
            'hash_id' => $this->hash_id,
            'massevent_id' => $this->massevent_id,
            'name_for_massevent' => $this->name_for_massevent,
            'phone_for_massevent' => $this->phone_for_massevent,
            'member_guid' => $this->member_guid,
        ];
    }
}
