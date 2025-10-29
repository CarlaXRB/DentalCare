<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $patientId = $this->route('patient')?->id ?? null;
        return [
            'name_patient' => 'required|string|max:100|min:3',
            'ci_patient' => [
                'required',
                'numeric',
                Rule::unique('patients', 'ci_patient')->ignore($patientId),
            ],
            'birth_date' => 'required|date',
            'gender' => 'required|in:masculino,femenino',
            'patient_contact' => 'required|numeric',
        ];
    }
}
