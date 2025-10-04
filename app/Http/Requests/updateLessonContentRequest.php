<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class updateLessonContentRequest extends FormRequest
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
            'subtitle' => 'sometimes|string|max:255',
            'content' => 'required|string|min:10',
            'url_resource' => 'sometimes|array',
            'url_resource.*' => 'nullable|url|max:2048',
            'files' => 'sometimes|array',
            'files.*' => 'file|mimetypes:image/*,video/*,application/pdf|max:5120',
            'remove_media_ids' => 'sometimes|array',
            'remove_media_ids.*' => 'integer|exists:media,id',
        ];
    }

    public function messages()
    {
        return [
            'subtitle.string' => 'El subtítulo debe ser un texto.',
            'content.required' => 'Especifica un contenido valido para tu lección.',

            'url_resource.array' => 'Los recursos deben ser una lista de URLs.',
            'url_resource.max' => 'Solo puedes agregar hasta 3 URLs como recursos.',
            'url_resource.*.url' => 'Cada recurso debe ser una URL válida.',
            'url_resource.*.max' => 'Cada URL no debe exceder los 2048 caracteres.',

            'files.required' => 'Sube al menos un archivo de contenido.',
            'files.array' => 'Los archivos deben enviarse como una lista.',
            'files.*.file' => 'Cada archivo debe ser un archivo válido.',
            'files.*.mimetypes' => 'Solo se permiten imágenes, videos o archivos PDF.',
            'files.*.max' => 'Cada archivo no debe superar los 5MB.',

            'remove_media_ids.array' => 'La lista de archivos a eliminar no es válida.',
            'remove_media_ids.*.integer' => 'El identificador de archivo a eliminar debe ser un número.',
            'remove_media_ids.*.exists' => 'Uno de los archivos a eliminar no existe en el sistema.',
        ];
    }
}
