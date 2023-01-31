<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class remisionRequest extends FormRequest
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
            'sal_noficio.required' => 'No. de oficio es obligatorio.',
            'sal_remiten.min'      => 'Remitente es de mínimo 1 caracteres.',
            'sal_remiten.max'      => 'Remitente es de máximo 100 caracteres.',
            'sal_remiten.required' => 'Remitente es obligatorio.',
            'sal_destin.min'       => 'Destinatario es de mínimo 1 caracteres.',
            'sal_destin.max'       => 'Destinatario es de máximo 100 caracteres.',
            'sal_destin.required'  => 'Destinatario es obligatorio.',
            'sal_asunto.min'       => 'Destinatario es de mínimo 1 caracteres.',
            'ent_asunto.max'       => 'Destinatario es de máximo 4,000 caracteres.',
            'sal_asunto.required'  => 'Destinatario es obligatorio.',
            'sal_uadmon.required'  => 'Unidad admon. remitente es obligatoria.',
            'sal_uadmon.min'       => 'Unidad admon. remitente es de mínimo 1 caracteres.',
            'sal_uadmon.max'       => 'Unidad admon. remitente es de máximo 100 caracteres.',            
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

            'sal_noficio'  => 'required',
            'sal_remiten'  => 'min:1|max:100|required',
            'sal_destin'   => 'min:1|max:200|required',
            'sal_asunto'   => 'min:1|max:4000|required',
            'sal_uadmon'   => 'min:1|max:100|required',
            'cve_sp'       => 'required',
            'tema_id'      => 'required'
        ];
    }
}
