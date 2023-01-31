<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class atenrecep2Request extends FormRequest
{
    public function messages() 
    {
        return [
            'periodo_id.required'  => 'Periodo fiscal es obligatorio.',
            'folio.required'       => 'Folio de sistema es obligatorio.', 
            'ent_arc2.required'    => 'El archivo digital PDF de seguimiento es obligatorio.'
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
            'ent_arc2'    => 'sometimes|mimetypes:application/pdf|max:1500'            
        ];
    }
}
