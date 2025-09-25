<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInstructorRequestRequest extends FormRequest
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
            'status' => 'pending_authorization',
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:instructor_requests,email',
            'phone' => 'nullable|string|max:20',
            'biography' => 'required|string|min:25|max:1000',
            'profile_photo_path' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB maximo
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre completo es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Ya existe una solicitud con este correo electrónico.',
            'biography.required' => 'La biografía es obligatoria.',
            'biography.min' => 'La biografía debe tener al menos 25 caracteres.',
            'biography.max' => 'La biografía no puede exceder 1000 caracteres.',
            'profile_photo_path.image' => 'El archivo debe ser una imagen.',
            'profile_photo_path.mimes' => 'La imagen debe ser formato JPEG, JPG o PNG.',
            'profile_photo_path.max' => 'La imagen no puede ser mayor a 2MB.',
        ];
    }
}
