<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class medioRequest extends FormRequest
{
    public function messages()
    {
        return [
            'medio_id.required'    => 'Medio informativo Id. es obligatorio.',
            'medio_desc.required'  => 'Medio informativo es obligatorio.',
            'medio_desc.min'       => 'Medio informativo es de mínimo 1 caracter.',
            'medio_desc.max'       => 'Medio informativo es de máximo 100 caracteres.',
            'medio_link.required'  => 'Link o URL es obligatorio.',
            'medio_foto1.required' => 'Logo es obligatorio.'
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
            'medio_id'     => 'required',
            'medio_desc'   => 'min:1|max:100|required',
            'medio_link'   => 'min:1|max:100|required',
            //'medio_foto1'=> 'sometimes|mimetypes:application/pdf|max:2048',
            'medio_foto1'  => 'mimes:jpg,jpeg,png|max:2048'
            //'tema_desc'  => 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
