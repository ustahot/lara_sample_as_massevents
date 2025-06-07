<?php

namespace App\Http\Resources\Massevent\MemberAnswer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberAnswerResource extends JsonResource
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
            'member_hash_id' => $this->member_hash_id,
            'status' => $this->status,
            'type' => $this->type,
            'answer' => $this->answer
        ];
    }
}
