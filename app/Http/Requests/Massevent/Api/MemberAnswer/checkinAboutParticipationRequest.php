<?php

namespace App\Http\Requests\Massevent\Api\MemberAnswer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class checkinAboutParticipationRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['request'] = json_encode($_REQUEST, JSON_UNESCAPED_UNICODE);
        $validated['type'] = 'participation_in_massevent';

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
            'member_hash_id' => 'required',
            'answer' => 'required', Rule::in([
                'refuse'
                , 'telegram'
                , 'call'
            ])
        ];
    }
}
