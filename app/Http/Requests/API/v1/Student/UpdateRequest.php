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
            'phone' => 'unique:students,phone,' . $this->request->all('id') . '|max:11',
            'email' => 'unique:students,email,' . $this->request->all('id') . '|max:255',
            'code' => 'unique:students,code,' . $this->user()->id . '|max:255',
            'vk_id' => 'unique:students,vk_id,' . $this->user()->id . '|max:255',
        ];
    }
}
