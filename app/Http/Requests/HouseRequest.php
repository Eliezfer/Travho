<?php

namespace App\Http\Requests;

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

use function PHPSTORM_META\type;
class HouseRequest extends FormRequest
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
    $this->states =array(
        'Aguascalientes',
        'Baja California',
        'Baja California Sur',
        'Campeche',
        'Coahuila de Zaragoza',
        'Colima',
        'Chiapas',
        'Chihuahua',
        'Ciudad de México',
        'Durango',
        'Guanajuato',
        'Guerrero',
        'Hidalgo',
        'Jalisco',
        'México',
        'Michoacán de Ocampo',
        'Morelos',
        'Nayarit',
        'Nuevo León',
        'Oaxaca de Juárez',
        'Puebla',
        'Querétaro',
        'Quintana Roo',
        'San Luis Potosí',
        'Sinaloa',
        'Sonora',
        'Tabasco',
        'Tamaulipas',
        'Tlaxcala',
        'Veracruz de Ignacio de la Llave',
        'Yucatán',
        'Zacatecas');

        return [
            'data.type'=>'required',
            'data.attributes.description'=>'required',
            'data.attributes.price_for_day'=>'gt:0|required',
            'data.attributes.state'=>'required|in:'.implode(',', $this->states),
            'data.attributes.municipality'=>'required',
            'data.address.street'=>'required',
            'data.address.cross_street1'=>'required',
            'data.address.cross_street2'=>'required',
            'data.address.house_number'=>'required',
            'data.address.suburb'=>'required',
            'data.address.postcode'=>'required|numeric'
        ];

    }
    public function messages()
    {
        return[
            'data.attributes.description.required' => 'La :attribute no es enviada en la solicitud',
            'data.attributes.price_for_day.gt'=> 'El :attribute debe ser un número mayor a 0',
            'data.attributes.price_for_day.required'=> 'El :attribute no es enviada en la solicitud',
            'data.attributes.state.required'=>'El :attribute no es enviada en la solicitud',
            'data.attributes.state.in'=>'El :attribute no es valido',
            'data.attributes.municipality.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.street.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.cross_street1.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.cross_street2.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.house_number.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.suburb.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.postcode.required'=>'El :attribute no es enviada en la solicitud',
            'data.address.postcode.numeric'=>'El :attribute debe ser un número',
        ];
    }
    public function attributes()
    {
        return [
            'data.attributes.description'=>'descripción',
            'data.attributes.price_for_day'=>'precio por día',
            'data.attributes.state'=>'estado',
            'data.attributes.municipality'=>'municipio',
            'data.address.street'=>'calle',
            'data.address.cross_street1'=>'cruzamiento 1',
            'data.address.cross_street2'=>'cruzamiento 2',
            'data.address.house_number'=>'número de casa',
            'data.address.suburb'=>'colonia',
            'data.address.postcode'=>'código postal'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        $error=[
            "code" => "ERROR-1",
            "title" =>"Uprocessable Entity",
            'message' => $errors
        ];
        throw new HttpResponseException(response()->json(['errors'=>[$error]],422));
    }
}
