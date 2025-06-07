<?php

namespace App\Http\Requests\Massevent\Api\Member;

use Illuminate\Foundation\Http\FormRequest;

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

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        if (isset($validated['phone_for_massevent'])){
            $digitPhone = preg_replace("/[^0-9]/", '', $validated['phone_for_massevent']);
            if (strlen($digitPhone) === 11 && str_starts_with($digitPhone, '8')) {
                $validated['phone_for_massevent'] = '7' . substr($digitPhone, 1);
            } else {
                $validated['phone_for_massevent'] = $digitPhone;
            }
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
            'massevent_id' => 'required',
            'category_id' => 'nullable',
            'name_for_massevent' => 'required',
//            'phone_for_massevent' => 'required|unique:massevents__members',
            'phone_for_massevent' => 'required',
            'email_for_massevent' => 'nullable',
            'ticket_plan' => 'nullable',
            'guid_1c' => 'nullable'
        ];
    }
}
