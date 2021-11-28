<?php

namespace App\Http\Requests\API\v1\Student;

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
            'id' => 'required|exists:students',
            'name' => 'max:255',
            'surname' => 'max:255',
            'patronymic' => 'max:255',
            'phone' => 'nullable|unique:students,phone,' . $this->input('id') . '|max:11',
            'email' => 'nullable|unique:students,email,' . $this->input('id') . '|max:255',
            'code' => 'nullable|unique:students,code,' . $this->input('id') . '|max:255',
            'vk_id' => 'nullable|unique:students,vk_id,' . $this->input('id') . '|max:255',
            'is_expelled' => 'required|boolean',
            'date_expelled' => 'nullable|required_if:is_expelled,1|date_format:Y-m-d',
        ];
    }
}
