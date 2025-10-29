<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MultimediaFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name_patient'=>['min:5'],
            'ci_patient'=>['min:5'],
            'file' => ['required', 'array'],
            'file.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,zip', 'max:51200'], 
            'study_type' => ['required', 'string', 'in:radiography,tomography,general'],
        ];
    }
    public function messages(): array
    {
        return [
            'file.required' => 'Debe seleccionar al menos un archivo para subir.',
            'file.*.mimes' => 'Solo se permiten archivos de imagen (jpg, jpeg, png) o archivos ZIP.',
            'file.*.max' => 'El tamaño máximo permitido por archivo es de 50MB.',
        ];
    }
}
