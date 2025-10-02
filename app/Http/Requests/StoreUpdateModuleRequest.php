<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateModuleRequest extends FormRequest
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
            'description' => 'required|string|min:25',
        ];

        if ($this->isMethod('post')) {
            $rules['id_course'] = 'required|integer';
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['id_course'] = 'nullable|integer';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required' => 'El titulo del modulo es requerido',
            'description.required' => 'Agrega una descripción para tu modulo',
            'description.min' => 'La descripción debe tener al menos 25 caracteres',
        ];
    }
}
