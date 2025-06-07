<?php

namespace App\Http\Requests\Massevent\App\TicketSet;

use App\Exceptions\Massevent\TicketSetException;
use App\Models\Massevent\Member;
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

    /**
     * @throws TicketSetException
     */
    public function customValidate(Member $member)
    {
        if ($member->ticket_set_id !== null) {
            throw new TicketSetException('Этому участнику уже выдавались билеты', 422);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
