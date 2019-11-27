<?php

namespace App\Http\Requests;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class BookingHouseCreateRequest extends FormRequest
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
            //
            'check_in'=> 'required|before:check_out|after:today',
            'check_out' => 'required|after:check_in',
            'house_id' => 'required|exists:houses,id'
        ];
    }

    public function messages()
    {
        return[
            'check_in.required' => 'La :attribute no es enviado en la solicitud',
            'check_in.before' => 'La :attribute debe ser una fecha antes del check_out',
            'check_in.after' => 'La :attribute debe ser una fecha despues de hoy',
            'check_out.required' => 'La :attribute no es enviado en la solicitud',
            'check_out.after' => 'La :attribute debe ser una fecha despues del check_in',
            'house_id.required' => 'El :attribute no es enviado en la solicitud',
            'house_id.exists' => 'El :attribute no existe'

        ];
    }

    public function attributes()
    {
        return [
            'check_in' => 'fecha de entrada',
            'check_out' => 'fecha de salida',
            'house_id' => 'id de la casa'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $json = [
            'code' => 'ERROR-1',
            'title' =>'Unprocessable Entity',
            'message' => $errors
        ];

        throw new HttpResponseException(
            response()->json(['errors' =>$json],
             JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
