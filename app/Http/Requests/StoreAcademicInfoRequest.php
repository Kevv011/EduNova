<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicInfoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        if (empty($this->institution) || trim($this->institution) === '') {
            $this->merge([
                'institution' => 'N/A',
                'status' => true,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'academic_level' => 'required|string',
            'institution' => 'nullable|string',
            'date_birth' => 'required|date',
            'user_id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'academic_level.required' => 'El nivel académico es requerido',
            'date_birth.required' => 'La fecha de nacimiento es requerida',
            'date_birth.date' => 'La fecha ingresada no es válida',
        ];
    }
}
