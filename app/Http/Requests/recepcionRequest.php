<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class recepcionRequest extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'  => 'Periodo fiscal es obligatorio.',
            'folio.required'       => 'Folio de sistema es obligatorio.',            
            'periodo_id1.required' => 'Año de fecha de oficio es obligatorio.',            
            'mes_id1.required'     => 'Mes de fecha de oficio es obligatorio.',            
            'dia_id1.required'     => 'Día de fecha de oficio es obligatorio.',             
            'periodo_id2.required' => 'Año de fecha de recibido es obligatorio.',
            'mes_id2.required'     => 'Mes de fecha de recibido es obligatorio.',
            'dia_id2.required'     => 'Dia de fecha de recibido es obligatorio.',
            'ent_noficio.required' => 'No. de oficio es obligatorio.',
            'ent_remiten.min'      => 'Remitente es de mínimo 1 caracteres.',
            'ent_remiten.max'      => 'Remitente es de máximo 100 caracteres.',
            'ent_remiten.required' => 'Remitente es obligatorio.',
            'ent_destin.min'       => 'Destinatario es de mínimo 1 caracteres.',
            'ent_destin.max'       => 'Destinatario es de máximo 100 caracteres.',
            'ent_destin.required'  => 'Destinatario es obligatorio.',
            'ent_asunto.min'       => 'Destinatario es de mínimo 1 caracteres.',
            'ent_asunto.max'       => 'Destinatario es de máximo 4,000 caracteres.',
            'ent_asunto.required'  => 'Destinatario es obligatorio.',
            'ent_uadmon.required'  => 'Unidad admon. remitente es obligatoria.',
            'ent_uadmon.min'       => 'Unidad admon. remitente es de mínimo 1 caracteres.',
            'ent_uadmon.max'       => 'Unidad admon. remitente es de máximo 100 caracteres.',            
            'cve_sp.required'      => 'Servidor público al que se turna el documento es obligatorio.',            
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
            'periodo_id'   => 'required',
            'folio'        => 'required',
            'periodo_id1'  => 'required',
            'mes_id1'      => 'required',
            'dia_id1'      => 'required',            
            'periodo_id2'  => 'required',
            'mes_id2'      => 'required',
            'dia_id2'      => 'required',

            'ent_noficio'  => 'required',
            'ent_remiten'  => 'min:1|max:100|required',
            'ent_destin'   => 'min:1|max:100|required',
            'ent_asunto'   => 'min:1|max:4000|required',
            'ent_uadmon'   => 'min:1|max:100|required',
            'cve_sp'       => 'required',
            'tema_id'      => 'required'
        ];
    }
}
