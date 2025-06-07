<?php

namespace App\Http\Requests\Massevent\Api\CallToMember;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }
//
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_from' => [
                'required', Rule::in([
                    '1000'
                    , '1001'
                    , '1002'
                    , '1003'
                    , '1004'
                    , '1005'
                    , '1006'
                    , '1007'
                ])]
        ];
    }
}
