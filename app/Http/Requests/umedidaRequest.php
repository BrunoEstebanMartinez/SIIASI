<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class umedidaRequest extends FormRequest
{
    public function messages()
    {
        return [
            'umed_id.required'   => 'Clave de la unidad de medida es obligatoria.',
            'umed_desc.required' => 'El nombre de la unidad de medida es obligatorio.',
            'umed_desc.min'      => 'El nombre de la unidad de medida es de mínimo 1 caracter.',
            'umed_desc.max'      => 'El nombre de la unidad de medida es de máximo 80 caracteres.',
            'umed_desc.regex'    => 'El nombre de la unidad de medida contiene caracteres inválidos.'
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
            'umed_id'    => 'required',
            'umed_desc'  => 'min:1|max:80|required'
            //'umed_desc'=> 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
