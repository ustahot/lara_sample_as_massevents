<?php

namespace App\Http\Requests\Massevent\Api\Massevent;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|unique:massevents__massevents|max:255',
            'massevent_date' => 'nullable',
            'massevent_time' => 'nullable',
            'type_id' => 'nullable',
            'place_id' => 'nullable',
            'name' => 'nullable',
            'description' => 'nullable',
            'total_ticket_max_quantity' => 'nullable',
            'member_ticket_max_quantity' => 'nullable',
            'guid_1c' => 'nullable',
            'booking_notify_message' => 'nullable',
            'reminder_tomorrow_message' => 'nullable',
            'reminder_today_message' => 'nullable',
            'feedback_request_message' => 'nullable',
        ];
    }
}
