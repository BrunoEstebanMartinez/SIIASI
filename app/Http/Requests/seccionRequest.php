<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class seccionRequest extends FormRequest
{
    public function messages()
    {
        return [
            'seccion_id.required'   => 'Clave de la Seccion es obligatoria.',
            'seccion_desc.required' => 'Seccion es obligatorio.',
            'seccion_desc.min'      => 'Seccion es de mínimo 1 caracter.',
            'seccion_desc.max'      => 'Seccion es de máximo 200 caracteres.',
            'seccion_desc.regex'    => 'Seccion contiene caracteres inválidos.',
            'seccion_tipo.min'      => 'Tipo de sección es de mínimo 1 caracter.',
            'seccion_tipo.max'      => 'Tipo de sección es de máximo 20 caracteres.',
            'seccion_tipo.regex'    => 'Tipo de sección contiene caracteres inválidos.'            
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
            'seccion_id'   =>  'min:1|max:10|required',
            'seccion_desc' =>  'min:1|max:200|required',
            'seccion_tipo' =>  'min:1|max:20|required'
            //'seccion_desc' => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
