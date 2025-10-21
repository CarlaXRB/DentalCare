<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RadiographyRequest extends FormRequest
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
            'ci_patient'=>['min:5'],
            'radiography_id'=>['nullable','max:100'],
            'radiography_date'=>['nullable','max:100'],
            'radiography_type'=>['nullable','max:100'],
            'radiography_file'=>['nullable'],
            'radiography_doctor'=>['nullable','max:100'],
            'radiography_charge'=>['nullable','max:100']
        ];
    }
}
