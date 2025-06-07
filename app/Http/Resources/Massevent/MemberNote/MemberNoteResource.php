<?php

namespace App\Http\Resources\Massevent\MemberNote;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberNoteResource extends JsonResource
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
            'content' => $this->content,
            'employee_name' => $this->employee->full_name ?? null,
            'created_at' => $this->created_at
        ];

    }
}
