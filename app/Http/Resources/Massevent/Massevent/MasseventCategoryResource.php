<?php

namespace App\Http\Resources\Massevent\Massevent;

use App\Http\Resources\Massevent\Member\MemberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MasseventCategoryResource extends JsonResource
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
            'name' => $this->name,
        ];
    }
}
