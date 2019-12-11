<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use App\BookingHouse;
use App\House;

class BookingHouseUpdateRequest extends FormRequest
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
        $booking = $this->route('bookingHouse');
        $house=House::findorfail($booking->house_id);
        
        if($this->user()->id = $house->user_id ){
            return [
                'status'=> [
                    'required',
                    Rule::in(['accepted','rejected']),
                ]
            ];
        }
        return [
            'check_in'=> 'before:check_out|after:today',
            'check_out' => 'after:check_in',
        ];
    }
    public function messages()
    {
        return[
            'check_in.before' => 'La :attribute debe ser una fecha antes del check_out',
            'check_in.after' => 'La :attribute debe ser una fecha despues de hoy',
            'check_out.after' => 'La :attribute debe ser una fecha despues del check_in',
            'status.required' => 'El status es requerido',
            'status.in' => 'El status solo puede ser aceptado o rechazado'
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
