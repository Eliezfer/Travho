<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;



class UserRequest extends FormRequest
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
            'data.attributes.name.required' => 'El :attribute no es enviado en la solicitud',
            'data.attributes.user.required' => 'El :attribute no es enviado en la solicitud',
            'data.attributes.password.required' => 'El :attribute no es enviado en la solicitud',
            'data.attributes.cellphone.required' => 'El :attribute no es enviado en la solicitud',
            'data.attributes.cellphone.numeric' => 'El :attribute debe ser un número',
            'data.attributes.email.email' => 'El :attribute debe tener formato test@correo.com ',
            'data.attributes.email.required' => 'El :attribute es requerido',
            'data.attributes.birthdate.required' => 'La :attribute es requerida',
            'data.attributes.birthdate.date' => 'La :attribute debe tener el formato [2/11/19]',
        ];
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

     // DATE_FORMAT (DATETIME CLASS)
     // email:rfc
    public function rules()
    {
        return [
            //
            'data.attributes.name'              =>  'required ',
            'data.attributes.user'              =>  'required',
            'data.attributes.password'          =>  'required', 
            'data.attributes.cellphone'         =>  'required | numeric',
            'data.attributes.email'             =>  'required | email ',
            'data.attributes.birthdate'         =>  'required | Date ',
        ];
    }
}
