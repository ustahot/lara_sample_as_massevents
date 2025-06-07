<?php

namespace App\Http\Resources\Massevent\MassSmsPlaceHolder;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MassSmsPlaceholderWithoutPhpVariableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->code,
            'description' => $this->description
        ];
    }
}
