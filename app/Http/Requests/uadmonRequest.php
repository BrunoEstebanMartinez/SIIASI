<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class uadmonRequest extends FormRequest
{
    public function messages()
    {
        return [
            'uadmon_id.required'  => 'Id. de la unidad administrativa es obligatoria.',
            'uadmon_desc.min'     => 'La unidad administrativa es de mínimo 1 caracter.',
            'uadmon_desc.max'     => 'La unidad administrativa es de máximo 80 caracteres.',
            'uadmon_desc.required'=> 'La unidad administrativa es obligatoria'
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
            'uadmon_id'   => 'required',
            'uadmon_desc' => 'required|min:1|max:80'
        ];
    }
}
