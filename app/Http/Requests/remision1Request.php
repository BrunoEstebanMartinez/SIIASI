<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class remision1Request extends FormRequest
{
    public function messages()
    {
        return [         
            'cve_sp.required'      => 'Servidor público quien envia el documento es obligatorio.',             
            'tema_id.required'     => 'Temática es obligatoria.'
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
            'cve_sp'       => 'required',
            'tema_id'      => 'required'
        ];
    }
}
