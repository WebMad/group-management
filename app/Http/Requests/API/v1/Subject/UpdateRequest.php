<?php

namespace App\Http\Requests\API\v1\Subject;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
        ];
    }
}
