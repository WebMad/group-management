<?php

namespace App\Http\Requests\API\v1\Student;

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
            'name' => 'max:255',
            'surname' => 'max:255',
            'patronymic' => 'max:255',
            'phone' => 'nullable|unique:students|max:11',
            'email' => 'nullable|unique:students|max:255',
            'code' => 'nullable|unique:students|max:255',
            'vk_id' => 'nullable|unique:students|max:255',
        ];
    }
}
