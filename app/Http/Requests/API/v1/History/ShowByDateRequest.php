<?php

namespace App\Http\Requests\API\v1\History;

use Illuminate\Foundation\Http\FormRequest;

class ShowByDateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date_format:Y-m-d'
        ];
    }
}
