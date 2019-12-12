<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
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
            'data.attributes.price_for_day'=>'numeric|gt:0|required',
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
            'data.attributes.description.required' => 'La :attribute no es enviado en la solicitud',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $error=[
            "code" => "ERROR-1",
            "title" =>"Uprocessable Entity"
        ];
        throw new HttpResponseException(response()->json(['errors'=>[$error]],422));
    }
}
