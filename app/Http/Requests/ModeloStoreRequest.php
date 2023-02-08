<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModeloStoreRequest extends FormRequest
{
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
            'marca_id'=>'exists:marcas,id',
            'nome' => 'required | max:15 | min:4',
            'imagem' =>'required',
            'numero_portas' => 'required',
            'lugares' => 'required',
            'air_bag' => 'required',
            'abs' => 'required'
        ];
    }
}
