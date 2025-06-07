<?php

namespace App\Http\Requests\Massevent\Api\MassSms;

use App\Exceptions\Massevent\MemberException;
use App\Models\Massevent\MassSms;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class RenderRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    public function customValidate($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        $massSms = MassSms::find($validated['mass_sms_id']);

        if (!isset($massSms)) {
            throw new MemberException(
                json_encode(['Не найдено подготовленное массовое SMS с id='
                    . $validated['mass_sms_id']], JSON_UNESCAPED_UNICODE)
                , Response::HTTP_UNPROCESSABLE_ENTITY);
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
            'mass_sms_id' => 'required',
        ];
    }
}
