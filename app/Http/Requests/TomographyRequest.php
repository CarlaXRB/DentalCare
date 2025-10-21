<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TomographyRequest extends FormRequest
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
        return [
            'name_patient'=>['min:5'],
            'ci_patient'=>['nullable','min:5'],
            'tomography_id'=>['nullable','max:100'],
            'tomography_date'=>['nullable','max:100'],
            'tomography_type'=>['nullable','max:100'],
            'tomography_file'=>['nullable'],
            'tomography_doctor'=>['nullable','max:100'],
            'tomography_charge'=>['nullable','max:100']
        ];
    }
}
