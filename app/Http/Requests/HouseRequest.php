<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.description'=>'required',
            'data.priceForDay'=>'numeric|gt:0|required',
            'data.status'=>'required',

            'address.country'=>'required',
            'address.state'=>'required',
            'address.municipality'=>'required',
            'address.street'=>'required',
            'address.crossStreet1'=>'required',
            'address.crossStreet2'=>'required',
            'address.houseNumber'=>'required',
            'address.suburb'=>'required',
            'postalCode'=>'required|numeric'
        ];
    }
}
