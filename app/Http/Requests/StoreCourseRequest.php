<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
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
        $this->merge([
            'available_status' => false,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'         => 'required|string',
            'description'  => 'required|string:min:25',
            'category_id'  => 'required|integer',
            'instructor_id' => 'required|integer',
            'available_status' => 'required|boolean',
            'areas'        => 'required|array',
            'image'        => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del curso es requerido',
            'description.required' => 'La descripcion del curso es requerido',
            'description.min' => 'Crea una descripcion de al menos 25 caracteres',
            'category_id.required' => 'La categoria del curso es requerido',
            'areas.required' => 'Las areas academicas del curso es requerido',
            'image.required' => 'La imagen del curso es requerido',
        ];
    }
}
