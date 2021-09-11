<?php

namespace App\Http\Requests\API\v1\SystemSetting;

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
            'name' => 'required|unique:system_settings',
            'value' => 'nullable'
        ];
    }
}
