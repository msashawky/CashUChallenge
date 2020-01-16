<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{

    //for security and manipulate with data
    public function toArray($request)
    {
        return [
            'provider' => $this->provider,
            'hotelName' => $this->hotelName,
            'fare' => $this->fare,
            'amenities' => $this->amenities
        ];
    }

}
