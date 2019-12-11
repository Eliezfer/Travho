<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    /*Definición de atributos para asignación masiva*/
    protected $fillable = [
        'country','state','municipality','address_id','user_id','description','price_for_day','status',
    ];

    

    /*Definicion de relaciones entre las tablas de la base de datos */
    public function address()
    {
        return $this->hasOne('App\Address','id','address_id');
        // se indica el modelo, la llave en la tabla foranea y la llave local correspondiente
    }
    public function user()
    {
        return $this->hasOne('App\User','id','user_id');
        // se indica el modelo, la llave en la tabla foranea y la llave local correspondiente
    }
}
