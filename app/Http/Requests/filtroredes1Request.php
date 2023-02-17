<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class filtroredes1Request extends FormRequest
{
    public function messages() 
    {
        return [
            'periodo_id1.required'   => 'Periodo inicial es obligatorio.',
            'mes_id1.required'       => 'El mes inicial es obligatorio.',
            'dia_id1.required'       => 'El dia inicial es obligatorio',
            'periodo_id2.required'   => 'Periodo final es obligatorio.',
            'mes_id2.required'       => 'El mes final es obligatorio.',
            'dia_id2.required'       => 'El dia final es obligatorio',
            'tipo.required'          => 'Tipo de estadistica es obligatorio'            
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
            'periodo_id1'  => 'required',
            'mes_id1'      => 'required',
            'dia_id1'      => 'required',
            'periodo_id2'  => 'required',
            'mes_id2'      => 'required',
            'dia_id2'      => 'required',
            'tipo'         => 'required'
            //'soporte_01' => 'sometimes|mimetypes:application/pdf,xls,xlsx,doc,docx|max:2500'            
        ];
    }
}
