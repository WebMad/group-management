<?php

namespace App\Http\Requests\API\v1\History;

use Illuminate\Foundation\Http\FormRequest;

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
            'date' => 'required|date_format:Y-m-d',
        ];
    }
}
