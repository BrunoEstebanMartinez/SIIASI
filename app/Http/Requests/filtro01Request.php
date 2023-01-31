<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class filtro01Request extends FormRequest
{
    public function messages()
    {
        return [
            'periodo_id.required'   => 'El periodo fiscal es obligatorio.', 
            'mes_id.required'       => 'El mes es obligatorio.',
            //'depen_id.required'   => 'La dependencia operativa es obligatoria.',
            'clase.required'        => 'Clase de reporte es obligatorio.',
            'tipo.required'         => 'Seleccionar el tipo es obligatorio.'
            //'vis_tipo2.required'  => 'Seleccionar el formato del reporte de salida, Excel o PDF.'
            //'vis_conto.required'  => 'El contacto de la IAP es obligatorio.',
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
            //'iap_desc.' => 'required|min:1|max:100',
            'periodo_id'  => 'required', 
            'mes_id'      => 'required',
            //'depen_id'  => 'required',
            'clase'       => 'required',
            'tipo'        => 'required'
            //'tipo2'     => 'required'
        ];
    }
}
