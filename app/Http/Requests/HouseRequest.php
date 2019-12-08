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
            'data.description'=>'required',
            'data.price_for_day'=>'numeric|gt:0|required',
            'data.status'=>'required',
            'data.country'=>'required',
            'data.state'=>'required|in:'.implode(',', $this->states),
            'data.municipality'=>'required',

            'address.street'=>'required',
            'address.cross_street1'=>'required',
            'address.cross_street2'=>'required',
            'address.house_number'=>'required',
            'address.suburb'=>'required',
            'address.postcode'=>'required|numeric'

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
