<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;


class AuthRequest extends FormRequest
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

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(   [
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
                "message" => $errors,
            ]]
        ] ,422)); 
    }
    public function messages()
    {
        return[
            'data.attributes.email.required' => 'El :attribute es requerido',
            'data.attributes.email.email' => 'El :attribute el formato del email no es correcto',
            'data.attributes.password.required' => 'La :attribute es requerido',
            
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            //
            'data.attributes.email'             =>  'required | email ',
            'data.attributes.password'          =>  'required',

        ];
    }
}
