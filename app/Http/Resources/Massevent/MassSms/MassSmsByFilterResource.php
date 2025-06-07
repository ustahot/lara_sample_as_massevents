<?php

namespace App\Http\Resources\Massevent\MassSms;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MassSmsByFilterResource extends JsonResource
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
            'real_used_phones_at_sending' => $this->real_used_phones_at_sending,
            'text' => $this->text,
            'phones_quantity' => count(json_decode($this->real_used_phones_at_sending, true)),
        ];
    }
}
