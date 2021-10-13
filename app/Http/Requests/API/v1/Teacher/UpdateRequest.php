<?php

namespace App\Http\Requests\API\v1\Teacher;

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
            'id' => 'required|exists:teachers',
            'name' => 'max:255',
            'surname' => 'max:255',
            'patronymic' => 'max:255',
            'phone' => 'nullable|unique:teachers,phone,' . $this->input('id') . '|max:11',
            'email' => 'nullable|unique:teachers,email,' . $this->input('id') . '|max:255',
        ];
    }
}
