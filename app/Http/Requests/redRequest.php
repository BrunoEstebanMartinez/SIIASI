<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class redRequest extends FormRequest
{
    public function messages()
    {
        return [
            'rs_id.required'    => 'Red social Id. es obligatorio.',
            'rs_desc.required'  => 'Red social es obligatorio.',
            'rs_desc.min'       => 'Red social es de mínimo 1 caracter.',
            'rs_desc.max'       => 'Red social es de máximo 100 caracteres.',
            //'rs_link.required'=> 'Link o URL es obligatorio.',
            'rs_foto1.required' => 'Logo es obligatorio.'
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
            'rs_id'     => 'required',
            'rs_desc'   => 'min:1|max:100|required',
            //'rs_link'   => 'min:1|max:100|required',
            //'rs_foto1'=> 'sometimes|mimetypes:application/pdf|max:2048',
            'rs_foto1'  => 'mimes:jpg,jpeg,png|max:2048'
            //'tema_desc'  => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
