<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class notaredRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'   => 'Periodo fiscal es obligatorio.',
            'rs_folio.required'     => 'Folio de sistema es obligatorio.',            
            //'periodo_id1.required'=> 'Año de fecha de oficio es obligatorio.',            
            //'mes_id1.required'    => 'Mes de fecha de oficio es obligatorio.',            
            //'dia_id1.required'    => 'Día de fecha de oficio es obligatorio.',             
            'rs_nota.required'      => 'No. de oficio es obligatorio.',
            'rs_nota.min'           => 'Nota es de mínimo 1 caracteres.',
            'rs_nota.max'           => 'Nota es de máximo 4000 caracteres.',
            'rs_titulo.required'    => 'Título es obligatorio.',
            'rs_titulo.min'         => 'Título es de mínimo 1 caracteres.',
            'rs_titulo.max'         => 'Título es de máximo 100 caracteres.',
            'rs_link.required'      => 'Link o URL es obligatorio.',
            'rs_id.required'        => 'Red social es obligatorio.'
            //'tipon_id.required'   => 'Tipo de nota es obligatoria.'
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
            'periodo_id'    => 'required',
            'rs_folio'      => 'required',
            //'periodo_id1' => 'required',
            //'mes_id1'     => 'required',
            //'dia_id1'     => 'required',            
            'rs_nota'       => 'min:1|max:4000|required',
            'rs_link'       => 'min:1|max:2000|required',
            'rs_titulo'     => 'min:1|max:3500|required',
            'rs_id'         => 'required'
            //'tipon_id'    => 'required'
        ];
    }
}
