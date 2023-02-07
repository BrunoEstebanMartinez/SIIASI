<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class turnarRequest extends FormRequest
{
    public function messages() 
    {
        return [
            'tema_id.required'     => 'Tematica es obligatoria.',
            'cve_sp.required'      => 'A quien se turna es obligatorio.', 
            'ent_status3.required' => 'Estado del turnado del documento es obligatorio.'
        ];
    }
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
     * @return array
     */
    public function rules()
    {
        return [
            'tema_id'     => 'required',
            'cve_sp'      => 'required',
            'ent_status3' => 'required'            
        ];
    }
}
