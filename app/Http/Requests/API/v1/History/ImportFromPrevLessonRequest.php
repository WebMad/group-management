<?php

namespace App\Http\Requests\API\v1\History;

use Illuminate\Foundation\Http\FormRequest;

class ImportFromPrevLessonRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'edu_history_id' => 'required|exists:education_history,id',
        ];
    }
}
