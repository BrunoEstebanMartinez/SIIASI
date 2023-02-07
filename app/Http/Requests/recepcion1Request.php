<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class recepcion1Request extends FormRequest
{
    public function messages() 
    {
        return [
            'periodo_id.required'  => 'Periodo fiscal es obligatorio.',
            'folio.required'       => 'Folio de sistema es obligatorio.', 
            'ent_arc1.required'    => 'El archivo digital PDF es obligatorio.'
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
            'periodo_id'  => 'required',
            'folio'       => 'required',
            'ent_arc1'    => 'sometimes|mimetypes:application/pdf|max:1500'            
        ];
    }
}
