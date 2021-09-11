<?php

namespace App\Http\Requests\API\v1\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject_id' => 'required|exists:subjects,id',
            'scheme_id' => 'required|exists:schedule_scheme,id',
            'week_type' => ['required', Rule::in([0, 1, 2])],
            'day_of_week' => ['required', Rule::in([0, 1, 2, 3, 4, 5, 6])],
            'start_week' => 'nullable|integer',
            'end_week' => 'nullable|integer',
            'address' => 'max:255'
        ];
    }
}
