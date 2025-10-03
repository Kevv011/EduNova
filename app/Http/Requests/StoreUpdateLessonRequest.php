<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string',
        ];

        if ($this->isMethod('post')) {
            $rules['module_id'] = 'required|integer';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['module_id'] = 'nullable|integer';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'El titulo de la lección es requerida',
        ];
    }
}
