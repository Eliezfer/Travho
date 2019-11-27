<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


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

        throw new HttpResponseException(response()->json(   [
            "errors" => [[
                "code" => "Error-1",
                "title" => "Unprocessable Entity",
            ]]
        ] ,422)); 
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
            'data.name'              =>  'required',
            'data.user'              =>  'required',
            'data.password'          =>  'required', // Mayor a tal número
            'data.cellphone'         =>  'required',
            'data.email'             =>  'required ',
            'data.birthdate'         =>  'required',
        ];
    }
}
