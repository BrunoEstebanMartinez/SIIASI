<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class red1Request extends FormRequest
{
    public function messages()
    {
        return [
            'rs_id.required'    => 'Red social Id. es obligatorio.',
            'rs_foto1.required' => 'Logo de la red social es obligatorio.'
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
            'rs_id'      => 'required',
            'rs_foto1'   => 'mimes:jpg,jpeg,png|max:2048'
            //'tema_desc'=> 'min:1|max:80|required|regex:/(^([a-zA-zñÑ%()=.\s\d]+)?$)/iñÑ'
        ];
    }
}
