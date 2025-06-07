<?php

namespace App\Http\Resources\Massevent\Massevent;

use App\Http\Resources\Massevent\Member\MemberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasseventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        $employee =

        return [
            'id' => $this->id,
            'code' => $this->code,
            'massevent_date' => $this->massevent_date,
            'massevent_time' => $this->massevent_time,
            'name' => $this->name,
            'total_ticket_max_quantity' => $this->total_ticket_max_quantity,
            'member_ticket_max_quantity' => $this->member_ticket_max_quantity,
            'description' => $this->description,
            'creator_employee_id' => $this->creator_employee_id,
            'members' => MemberResource::collection($this->members),
        ];
    }
}
