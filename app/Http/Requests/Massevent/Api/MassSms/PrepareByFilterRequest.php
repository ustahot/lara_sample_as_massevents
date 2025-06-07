<?php

namespace App\Http\Requests\Massevent\Api\MassSms;

use App\Models\Massevent\Massevent;
use Illuminate\Foundation\Http\FormRequest;

class PrepareByFilterRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }
//
    public function customValidate(Massevent $massevent)
    {
        $validated = parent::validated();
        $validated['massevent_id'] = $massevent->id ?? null;
        if (isset($validated['status_list'])) {
            $validated['status_list'] = mb_strtolower($validated['status_list']);
        }

        return $validated;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => 'required',
            'member_quantity' => 'required',
            'category_id' => 'nullable',
            'status_list' => 'nullable',
            'find_fragment' => 'nullable'
        ];
    }
}
