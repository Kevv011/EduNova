<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequestRequest extends FormRequest
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
        return [
            'academic_level' => 'required|string',
            'subject_specialties' => 'required|array|min:1',
            'schedule_availability' => 'required|array|min:1|max:7',
            'subjects' => 'required|string',
            'level_education' => 'required|array|min:1',
            'certificates' => 'nullable|array|max:5',
            'certificates.*' => 'file|mimes:jpeg,jpg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'academic_level.required' => 'El nivel académico es obligatorio.',
            'subject_specialties.required' => 'Debes seleccionar al menos un área de especialización.',
            'subject_specialties.min' => 'Debes seleccionar al menos un área de especialización.',
            'schedule_availability.required' => 'Debes seleccionar al menos un día de disponibilidad.',
            'schedule_availability.min' => 'Debes seleccionar al menos un día de disponibilidad.',
            'subjects.required' => 'Las materias que impartes son obligatorias.',
            'subjects.min' => 'Describe al menos 50 caracteres sobre las materias que impartes.',
            'level_education.required' => 'Debes especificar a qué nivel educativo está destinada tu enseñanza.',
            'certificates.max' => 'No puedes subir más de 5 certificaciones.',
            'certificates.*.mimes' => 'Las certificaciones deben ser JPG, PNG, JPEG.',
            'certificates.*.max' => 'Cada certificación no puede exceder 2MB.',
        ];
    }
}
