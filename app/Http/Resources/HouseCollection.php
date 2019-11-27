<?php

namespace App\Http\Resources;
use App\Http\Resources\House as HouseResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class HouseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->transform(function (House $House) {
            return (new HouseResource($House));
        });

        return $this->collection;
    }
}
