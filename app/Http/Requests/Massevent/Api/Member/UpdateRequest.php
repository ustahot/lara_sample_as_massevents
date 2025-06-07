<?php

namespace App\Http\Requests\Massevent\Api\Member;

use App\Exceptions\Massevent\MemberException;
use App\Models\Massevent\Member;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    private mixed $throw;

    /**
     * @throws MemberException
     */
    public function validated($key = null, $default = null)
    {

        $validated = parent::validated($key, $default);

        $errors = [];

        $rows = Member::where([
            'massevent_id' => $this->member->massevent_id,
            'phone_for_massevent' => $this->get('phone_for_massevent'),
        ])->wherenot('id', $this->member->id)->get();

        if ($rows->count() > 0) {
            $errors[] = "В мерприятии с id={$this->member->massevent_id} существует другой участник с номером
                телефона {$this->member->phone_for_massevent}";
        }

        if (null !== $this->get('email_for_massevent')) {
            $rows = Member::where([
                'massevent_id' => $this->member->massevent_id,
                'email_for_massevent' => $this->get('email_for_massevent'),
            ])->wherenot('id', $this->member->id)->get();

            if ($rows->count() > 0) {
                $errors[] = "В мерприятии с id={$this->member->massevent_id} существует другой участник с email
                {$this->member->email_for_massevent}";
            }
        }

        if (count($errors) > 0) {
            throw new MemberException(json_encode($errors, JSON_UNESCAPED_UNICODE), 422);
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
            'name_for_massevent' => 'nullable',
            'phone_for_massevent' => 'nullable',
            'email_for_massevent' => 'nullable',
            'ticket_plan' => 'nullable'
        ];
    }
}
