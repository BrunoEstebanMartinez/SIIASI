<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class notaperRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'   => 'Periodo fiscal es obligatorio.',
            'nm_folio.required'     => 'Folio de sistema es obligatorio.',            
            //'periodo_id1.required'=> 'Año de fecha de oficio es obligatorio.',            
            //'mes_id1.required'    => 'Mes de fecha de oficio es obligatorio.',            
            //'dia_id1.required'    => 'Día de fecha de oficio es obligatorio.',             
            'nm_nota.required'      => 'No. de oficio es obligatorio.',
            'nm_nota.min'           => 'Nota es de mínimo 1 caracteres.',
            'nm_nota.max'           => 'Nota es de máximo 4000 caracteres.',
            'nm_titulo.required'    => 'Título es obligatorio.',
            'nm_titulo.min'         => 'Título es de mínimo 1 caracteres.',
            'nm_titulo.max'         => 'Título es de máximo 100 caracteres.',
            'nm_link.required'      => 'Link o URL es obligatorio.',
            'medio_id.required'     => 'Medio informativo es obligatorio.',            
            'tipon_id.required'     => 'Tipo de nota es obligatoria.'
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
            'nm_folio'      => 'required',
            //'periodo_id1' => 'required',
            //'mes_id1'     => 'required',
            //'dia_id1'     => 'required',            
            'nm_nota'       => 'min:1|max:4000|required',
            'nm_link'       => 'min:1|max:2000|required',
            'nm_titulo'     => 'min:1|max:3500|required',
            'medio_id'      => 'required',
            'tipon_id'      => 'required'
        ];
    }
}
