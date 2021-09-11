<?php

namespace App\Http\Requests\API\v1\SystemSetting;

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
            'name' => 'required|unique:system_settings,name,' . $this->route('system_setting'),
            'value' => 'nullable'
        ];
    }
}
