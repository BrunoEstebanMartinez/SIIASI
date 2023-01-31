<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class temasRequest extends FormRequest
{
    public function messages()
    {
        return [
            'tema_no.required'    => 'La clave de la Serie o temática es obligatoria.',
            'tema_id.required'    => 'Id. de la Serie o temática es obligatoria.',
            'tema_desc.required'  => 'Serie o Temática es obligatorio.',
            'tema_desc.min'       => 'Serie o Temática es de mínimo 1 caracter.',
            'tema_desc.max'       => 'Serie o Temática es de máximo 250 caracteres.',
            'tema_desc.regex'     => 'Serie o Temática contiene caracteres inválidos.',
            'seccion_id.required' => 'La Sección es obligatoria.'
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
            'tema_no'    => 'required',        
            'tema_id'    => 'required',
            'tema_desc'  => 'min:1|max:250|required',
            'seccion_id' => 'required'
            //'tema_desc'=> 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
